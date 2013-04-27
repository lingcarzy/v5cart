<?php

static $config = NULL;
static $log = NULL;

// Error Handler
function error_handler_for_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}

	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}


function fatal_error_shutdown_handler_for_export()
{
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}


class ModelToolExport extends Model {

	private $import_mode = 'insert';
	private $productModelIds = array();
	
	function clean( &$str, $allowBlanks=FALSE ) {
		$result = "";
		$n = strlen( $str );
		for ($m=0; $m<$n; $m++) {
			$ch = substr( $str, $m, 1 );
			if (($ch==" ") && (!$allowBlanks) || ($ch=="\n") || ($ch=="\r") || ($ch=="\t") || ($ch=="\0") || ($ch=="\x0B")) {
				continue;
			}
			$result .= $ch;
		}
		return $result;
	}


	function import( &$database, $sql ) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$database->query($sql);
			}
		}
	}


	protected function getDefaultLanguageId( &$database ) {
		$code = C('config_language');
		$sql = "SELECT language_id FROM `@@language` WHERE code = '$code'";
		$result = $database->query( $sql );
		$languageId = 1;
		if ($result->num_rows) {
			$languageId = $result->row['language_id'];
		}
		return $languageId;
	}

	function storeManufacturersIntoDatabase( &$database, &$products, &$manufacturerIds ) {
		// find all manufacturers already stored in the database
		$sql = "SELECT `manufacturer_id`, `name` FROM `@@manufacturer`;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$manufacturerId = $row['manufacturer_id'];
				$name = $row['name'];
				if (!isset($manufacturerIds[$name])) {
					$manufacturerIds[$name] = $manufacturerId;
				} else if ($manufacturerIds[$name] < $manufacturerId) {
					$manufacturerIds[$name] = $manufacturerId;
				}
			}
		}

		// add newly introduced manufacturers to the database
		$maxManufacturerId=0;
		foreach ($manufacturerIds as $manufacturerId) {
			$maxManufacturerId = max( $maxManufacturerId, $manufacturerId );
		}
		$sql = "INSERT INTO `@@manufacturer` (`manufacturer_id`, `name`, `image`, `sort_order`) VALUES ";
		$sql2 = "INSERT INTO `@@manufacturer_to_store` (`manufacturer_id`,`store_id`) VALUES ";

		$k = strlen( $sql );
		$first = TRUE;
		foreach ($products as $product) {
			if (empty($product['manufacturer'])) {
				continue;
			}
			$manufacturerName = $product['manufacturer'];
			if (!isset($manufacturerIds[$manufacturerName])) {
				$maxManufacturerId += 1;
				$manufacturerId = $maxManufacturerId;
				$manufacturerIds[$manufacturerName] = $manufacturerId;
				$sql .= ($first) ? "\n" : ",\n";
				$sql .= "($manufacturerId, '".$database->escape($manufacturerName)."', '', 0)";
				$sql2 .= ($first) ? "\n" : ",\n";
				$sql2 .= "($manufacturerId, 0)";
				$first = FALSE;
			}
		}
		$sql .= ";\n";
		$sql2 .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
			$database->query( $sql2);
		}
		return TRUE;
	}

	function storeSuppliersIntoDatabase( &$database, &$products, &$supplierIds ) {
		// find all suppliers already stored in the database
		$sql = "SELECT `supplier_id`, `name` FROM `@@supplier`;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$id = $row['supplier_id'];
				$name = $row['name'];
				if (!isset($supplierIds[$name])) {
					$supplierIds[$name] = $id;
				} else if ($supplierIds[$name] < $id) {
					$supplierIds[$name] = $id;
				}
			}
		}

		// add newly introduced suppliers to the database
		$maxSupplierId=0;
		foreach ($supplierIds as $id) {
			$maxSupplierId = max( $maxSupplierId, $id );
		}
		$sql = "INSERT INTO `@@supplier` (`supplier_id`, `name`) VALUES ";
		
		$k = strlen( $sql );
		$first = TRUE;
		foreach ($products as $product) {
			if (empty($product['supplier'])) {
				continue;
			}
			$supplierName = $product['supplier'];
			if (!isset($supplierIds[$supplierName])) {
				$maxSupplierId += 1;
				$id = $maxSupplierId;
				$supplierIds[$supplierName] = $id;
				$sql .= ($first) ? "\n" : ",\n";
				$sql .= "($id, '".$database->escape($supplierName)."')";
				$first = FALSE;
			}
		}
		$sql .= ";\n";
		if (strlen( $sql ) > $k+2) {
			$database->query( $sql );
		}
		return TRUE;
	}
	
	function getWeightClassIds( &$database ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);

		// find all weight classes already stored in the database
		$weightClassIds = array();
		$sql = "SELECT `weight_class_id`, `unit` FROM `@@weight_class_description` WHERE `language_id`=$languageId;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$weightClassId = $row['weight_class_id'];
				$unit = $row['unit'];
				if (!isset($weightClassIds[$unit])) {
					$weightClassIds[$unit] = $weightClassId;
				}
			}
		}

		return $weightClassIds;
	}


	function getLengthClassIds( &$database ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);

		// find all length classes already stored in the database
		$lengthClassIds = array();
		$sql = "SELECT `length_class_id`, `unit` FROM `@@length_class_description` WHERE `language_id`=$languageId;";
		$result = $database->query( $sql );
		if ($result->rows) {
			foreach ($result->rows as $row) {
				$lengthClassId = $row['length_class_id'];
				$unit = $row['unit'];
				if (!isset($lengthClassIds[$unit])) {
					$lengthClassIds[$unit] = $lengthClassId;
				}
			}
		}

		return $lengthClassIds;
	}
	
	function getUpdateSql($table, $data, $idField, $where = '') {
    	$t = array();
    	if(!empty($idField)) {
    		$idValue = $data[$idField];
    		unset($data[$idField]);
    		$where = "$idField = '$idValue'";
    	} else if(empty($where)){
    		return 'ERROR: you must specify the $idField or $where';
    	}
    	foreach($data as $field => $value) {
    		$t[] = "$field = '" . $value . "'";
    	}
    	return "UPDATE $table SET ".implode(", ", $t)." WHERE $where";
    }

	function storeProductsIntoDatabase( &$database, &$products, $heading)
	{
		
		// find the default language id and default units
		$languageId = $this->getDefaultLanguageId($database);
		$defaultStockStatusId = C('config_stock_status_id');

		// store or update manufacturers
		$manufacturerIds = array();
		$supplierIds = array();
		$this->storeManufacturersIntoDatabase( $database, $products, $manufacturerIds );
		$this->storeSuppliersIntoDatabase( $database, $products, $supplierIds );
		// get weight classes
		$weightClassIds = $this->getWeightClassIds( $database );
		// get length classes
		$lengthClassIds = $this->getLengthClassIds( $database );

		// start transaction
		$database->query("START TRANSACTION;");

		// generate and execute SQL for storing the products
		if ($this->import_mode == 'update') {
			$intArray = array('quantity','cate_id','points','stock_status_id','minimum','sort_order');
			$strArray = array('model','ean', 'jan', 'isbn', 'mpn', 'sku', 'upc', 'location','seo_url', 'source_link');
			foreach($products as $product) {
				$productId = $product['product_id'];
				$productDescription = array();
				foreach($heading as $column) {
					if ($column == 'manufacturer') {
						$product['manufacturer_id'] = $manufacturerIds[$product['manufacturer']];
						unset($product['manufacturer']);
					}
					if ($column == 'supplier') {
						$product['supplier_id'] = $supplierIds[$product['supplier']];
						unset($product['supplier']);
					}
					if ($column == 'weight_class') {
						$product['weight_class_id'] = $weightClassIds[$product['weight_class']];
						unset($product['weight_class']);
					}
					elseif ($column == 'length_class') {
						$product['length_class_id'] = $lengthClassIds[$product['length_class']];
						unset($product['length_class']);
					}
					elseif ($column == 'categories') {
						if (!empty($product['categories'])) {
							$database->query("DELETE FROM `@@product_to_category` WHERE product_id=$productId");
							$categories = explode(',', $product['categories']);
							$sql = "INSERT INTO `@@product_to_category` (`product_id`,`category_id`) VALUES ";
							$first = TRUE;
							foreach ($categories as $categoryId) {
								$sql .= ($first) ? "\n" : ",\n";
								$first = FALSE;
								$sql .= "($productId,$categoryId)";
							}
							$sql .= ";";
							$database->query($sql);
						}
						unset($product['categories']);
					}
					elseif ($column == 'additional_images') {
						$database->query("DELETE FROM `@@product_image` WHERE product_id=$productId");
						$imageNames = trim( $this->clean($product['additional_images'], TRUE) );
						$imageNames = ($imageNames == "") ? array() : explode( ",", $imageNames );
						foreach ($imageNames as $imageName) {
							$sql = "INSERT INTO `@@product_image` (product_id, `image`) VALUES ";
							$sql .= "($productId,'$imageName');";
							$database->query( $sql );
						}
						unset($product['additional_images']);
					}
					elseif ($column == 'description' || $column == 'meta_description' 
							|| $column == 'meta_keyword' || $column == 'name' || $column == 'seo_title' || $column == 'tag') {
						$productDescription[$column] = $database->escape($product[$column]);
						unset($product[$column]);
					}
					elseif ($column == 'shipping') {
						$product['shipping'] = ((strtoupper($product['shipping'])=="YES") || (strtoupper($product['shipping'])=="Y") || (strtoupper($product['shipping'])=="TRUE")) ? 1 : 0;
					}
					elseif ($column == 'status' || $column == 'subtract') {
						$product[$column] = ((strtoupper($product[$column])=="TRUE") || (strtoupper($product[$column])=="YES") || (strtoupper($product[$column])=="ENABLED")) ? 1 : 0;
					}
					elseif ($column == 'date_available') {
						if (empty($product['date_available']))
							$product['date_available'] = date('Y-m-d');
					}
					elseif (in_array($column, $intArray)) {
						$product[$column] = intval($product[$column]);
					}
					elseif (in_array($column, $strArray)) {
						$product[$column] = $database->escape($product[$column]);
					}
				}
				
				$product['date_modified'] = date('Y-m-d H:i:s');
				$sql = $this->getUpdateSql(DB_PREFIX.'product', $product, 'product_id');
				$database->query($sql);
				if (!empty($productDescription)) {
					$sql = $this->getUpdateSql(DB_PREFIX."product_description", $productDescription, null, "product_id = $productId");
					$database->query($sql);
				}
			}
		}
		else {
			$result = $database->query("SELECT MAX(product_id) AS id FROM `@@product`");
			$productId = $result->rows[0]['id'];
			foreach ($products as $product) {
				$productId++;				
				$quantity = intval($product['quantity']);
				$model = $database->escape($product['model']);
				//
				$this->productModelIds[$model] = $productId;
				
				$manufacturerName = $product['manufacturer'];
				$manufacturerId = empty($manufacturerName) ? 0 : $manufacturerIds[$manufacturerName];
				$supplierName = $product['supplier'];
				$supplierId = empty($supplierName) ? 0 : $supplierIds[$supplierName];
				$imageName = $product['image'];
				$shipping = $product['shipping'];
				$shipping = ((strtoupper($shipping)=="YES") || (strtoupper($shipping)=="Y") || (strtoupper($shipping)=="TRUE")) ? 1 : 0;
				$msrp = empty($product['msrp']) ? 0: trim($product['msrp']);
				$cost = empty($product['cost']) ? 0: trim($product['cost']);
				$price = trim($product['price']);
				$points = intval($product['points']);
				$dateAdded = 'NOW()';
				$dateModified = 'NOW()';
				$dateAvailable = $product['date_available'];
				if (empty($dateAvailable)) $dateAvailable = 'NOW()';
				$weight = intval($product['weight']);
				$unit = $product['weight_class'];
				$weightClassId = (isset($weightClassIds[$unit])) ? $weightClassIds[$unit] : 0;
				$status = $product['status'];
				$status = ((strtoupper($status)=="TRUE") || (strtoupper($status)=="YES") || (strtoupper($status)=="ENABLED")) ? 1 : 0;
				$taxClassId = $product['tax_class_id'];
				$viewed = 0;				
				$stockStatusId = intval($product['stock_status_id']);
				$stockStatusId = $stockStatusId > 0 ? $stockStatusId : $defaultStockStatusId;
				$length = $product['length'];
				$width = $product['width'];
				$height = $product['height'];
				$lengthUnit = $product['length_class'];
				$lengthClassId = (isset($lengthClassIds[$lengthUnit])) ? $lengthClassIds[$lengthUnit] : 0;
				$sku = $database->escape($product['sku']);
				$upc = $database->escape($product['upc']);
				$location = $database->escape($product['location']);
				$subtract = $product['subtract'];
				$subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
				$minimum = intval($product['minimum']);
				$sort_order = intval($product['sort_order']);
				$seo_url = $product['seo_url'];
				//product description
				$productName = $database->escape($product['name']);
				$productDescription = $database->escape($product['description']);
				$seo_title = $database->escape($product['seo_title']);
				$meta_keyword = $database->escape($product['meta_keyword']);
				$tag = $database->escape($product['tag']);
				$meta_description = $database->escape($product['meta_description']);
				
				$sql  = "INSERT INTO `@@product` (`product_id`,`quantity`,`sku`,`upc`,`location`,";
				$sql .= "`stock_status_id`,`model`,`manufacturer_id`,`supplier_id`,`image`,`shipping`,`msrp`,`cost`,`price`,`points`,`date_added`,`date_modified`,`date_available`,`weight`,`weight_class_id`,`status`,";
				$sql .= "`tax_class_id`,`viewed`,`length`,`width`,`height`,`length_class_id`,`sort_order`,`subtract`,`minimum`,seo_url) VALUES ";
				$sql .= "($productId,$quantity,'$sku','$upc','$location',";
				$sql .= "$stockStatusId,'$model',$manufacturerId,$supplierId,'$imageName',$shipping,$msrp, $cost,$price,$points,";
				$sql .= ($dateAdded=='NOW()') ? "$dateAdded," : "'$dateAdded',";
				$sql .= ($dateModified=='NOW()') ? "$dateModified," : "'$dateModified',";
				$sql .= ($dateAvailable=='NOW()') ? "$dateAvailable," : "'$dateAvailable',";
				$sql .= "$weight,$weightClassId,$status,";
				$sql .= "$taxClassId,$viewed,$length,$width,$height,'$lengthClassId','$sort_order','$subtract','$minimum','$seo_url');";
				$sql2 = "INSERT INTO `@@product_description` (`product_id`,`language_id`,`name`,`description`,`seo_title`,`meta_description`,`meta_keyword`,tag) VALUES ";
				$sql2 .= "($productId,$languageId,'$productName','$productDescription', '$seo_title','$meta_description','$meta_keyword','$tag');";
				$database->query($sql);
				$database->query($sql2);
				
				//additional images
				$imageNames = trim( $this->clean($product['additional_images'], TRUE) );
				$imageNames = ($imageNames=="") ? array() : explode( ",", $imageNames );
				foreach ($imageNames as $imageName) {
					$sql = "INSERT INTO `@@product_image` (product_id, `image`) VALUES ";
					$sql .= "($productId,'$imageName');";
					$database->query( $sql );
				}
				
				//categories				
				if (!empty($product['categories'])) {
					$categories = explode(',', $product['categories']);
					$sql = "INSERT INTO `@@product_to_category` (`product_id`,`category_id`) VALUES ";
					$first = TRUE;
					foreach ($categories as $categoryId) {
						$sql .= ($first) ? "\n" : ",\n";
						$first = FALSE;
						$sql .= "($productId,$categoryId)";
					}
					$sql .= ";";
					$database->query($sql);
				}
				
				// product to store
				$sql6 = "INSERT INTO `@@product_to_store` (`product_id`,`store_id`) VALUES ($productId, 0);";
				$database->query($sql6);
			}
		}
		// final commit
		$database->query("COMMIT;");
		return TRUE;
	}


	protected function detect_encoding( $str ) {
		// auto detect the character encoding of a string
		return mb_detect_encoding( $str, 'UTF-8,ISO-8859-15,ISO-8859-1,cp1251,KOI8-R' );
	}


	function uploadProducts( &$reader, &$database ) {
		$data = $reader->getSheetByName("Products");
		$products = array();
		$product = array();
		$heading = array();

		$columnCount = PHPExcel_Cell::columnIndexFromString( $data->getHighestColumn() );
		for ($j=1; $j <= $columnCount; $j+=1) {
			$heading[] = $this->getCell($data, 0, $j);
		}

		$k = $data->getHighestRow();
		for ($i=1; $i < $k; $i+=1) {
			for ($j = 1; $j <= $columnCount; $j++) {
				if ($heading[$j-1] == 'name'
					|| $heading[$j-1] == 'description'
					|| $heading[$j-1] == 'summary'
					|| $heading[$j-1] == 'seo_title'
					|| $heading[$j-1] == 'meta_description'
					|| $heading[$j-1] == 'meta_keyword') {
					$val = $this->getCell($data, $i, $j);
					$product[$heading[$j-1]] = htmlentities( $val, ENT_QUOTES, $this->detect_encoding($val) );
				} else {
					$product[$heading[$j-1]] = $this->getCell($data, $i, $j);
				}
			}
			$products[] = $product;
		}
		
		return $this->storeProductsIntoDatabase( $database, $products, $heading );
	}

	function storeOptionsIntoDatabase( &$database, &$options )
	{
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);

		//option_ids and option_value_ids where possible
		$optionIds = array();       // indexed by [name][type]
		$optionValueIds = array();  // indexed by [name][type][value]
		$sql  = "SELECT o.*, od.name, ovd.option_value_id, ovd.name AS value FROM `@@option` o ";
		$sql .= "INNER JOIN `@@option_description` od ON od.option_id=o.option_id AND od.language_id=$languageId ";
		$sql .= "LEFT JOIN  `@@option_value_description` ovd ON ovd.option_id=o.option_id AND ovd.language_id=$languageId ";
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$name = $row['name'];
			$type = $row['type'];
			$value = $row['value'];
			$optionId = $row['option_id'];
			$optionValueId = $row['option_value_id'];
			
			if (!isset($optionIds[$name])) {
				$optionIds[$name] = array();
			}
			if (!isset($optionIds[$name][$type])) {
				$optionIds[$name][$type] = $optionId;
			}
			if (!isset($optionValueIds[$name])) {
				$optionValueIds[$name] = array();
			}
			if (!isset($optionValueIds[$name][$type])) {
				$optionValueIds[$name][$type] = array();
			}
			if (!isset($optionValueIds[$name][$type][$value])) {
				$optionValueIds[$name][$type][$value] = $optionValueId;
			}
		}

		// start transaction, remove options
		$oldOptions = array();
		foreach ($options as $option) {
			$oldOptions[] = $option['product_id'];
		}
		$oldOptions = implode(',', $oldOptions);
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `@@product_option` WHERE product_id IN ($oldOptions);\n";
		$sql .= "DELETE FROM `@@product_option_value` WHERE product_id IN ($oldOptions);\n";
		$this->import( $database, $sql );
		$productOptionIds = array();
		foreach ($options as $option) {
			$productId = $option['product_id'];
			$name = $option['option'];
			$type = $option['type'];
			$value = $option['value'];
			$required = $option['required'];
			$required = ((strtoupper($required)=="TRUE") || (strtoupper($required)=="YES") || (strtoupper($required)=="ENABLED")) ? 1 : 0;
			$optionId = isset($optionIds[$name][$type]) ? $optionIds[$name][$type] : 0;
			$optionValueId = isset($optionValueIds[$name][$type][$value]) 
				? $optionValueId = $optionValueIds[$name][$type][$value] 
				: 0;
			if (($type!='select') && ($type!='checkbox') && ($type!='radio')) {
				$productOptionValue = $value;
			} else {
				$productOptionValue = '';
			}
			if (!isset($productOptionIds[$productId][$optionId])) {
				$sql  = "INSERT INTO `@@product_option` (`product_option_id`,`product_id`,`option_id`,`option_value`,`required`) VALUES ";
				$sql .= "(NULL, $productId, $optionId,'".$database->escape($productOptionValue)."',$required);";
				$database->query( $sql );
				$productOptionIds[$productId][$optionId] = $database->getLastId();
			}
			if (($type=='select') || ($type=='checkbox') || ($type=='radio')) {
				$quantity = $option['quantity'];
				$subtract = $option['subtract'];
				$subtract = ((strtoupper($subtract)=="TRUE") || (strtoupper($subtract)=="YES") || (strtoupper($subtract)=="ENABLED")) ? 1 : 0;
				$price = $option['price'];
				$pricePrefix = $option['price_prefix'];
				$points = $option['points'];
				$pointsPrefix = $option['points_prefix'];
				$weight = $option['weight'];
				$weightPrefix = $option['weight_prefix'];
				$productOptionId = $productOptionIds[$productId][$optionId];
				$sql  = "INSERT INTO `@@product_option_value` (`product_option_value_id`,`product_option_id`,`product_id`,`option_id`,`option_value_id`,`quantity`,`subtract`,`price`,`price_prefix`,`points`,`points_prefix`,`weight`,`weight_prefix`) VALUES ";
				$sql .= "(NULL,$productOptionId,$productId,$optionId,$optionValueId,$quantity,$subtract,$price,'$pricePrefix',$points,'$pointsPrefix',$weight,'$weightPrefix');";
				$database->query( $sql );
			}
		}

		$database->query("COMMIT;");
		return TRUE;
	}

	function storeAttributesIntoDatabase( &$database, &$attributes ) {
		// find the default language id
		$languageId = $this->getDefaultLanguageId($database);

		$attributeIds = array();         // indexed by [group][name]
		$sql  = "SELECT agd.name AS `group`, ad.attribute_id, ad.name FROM `@@attribute_group` ag ";
		$sql .= "INNER JOIN `@@attribute_group_description` agd ON agd.attribute_group_id=ag.attribute_group_id AND agd.language_id=$languageId ";
		$sql .= "LEFT JOIN  `@@attribute` a ON a.attribute_group_id=ag.attribute_group_id ";
		$sql .= "INNER JOIN  `@@attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id=$languageId ";
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$attributeId = $row['attribute_id'];
			$group = $row['group'];
			$name = $row['name'];
			if (!isset($attributeIds[$group][$name])) {
				$attributeIds[$group][$name] = $attributeId;
			}
		}
		
		// start transaction, remove attributes
		$oldAttributes = array();
		foreach ($attributes as $attribute) {
			$oldAttributes[] = $attribute['product_id'];
		}
		$oldAttributes = implode(',', $oldAttributes);
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `@@product_attribute` WHERE product_id IN ($oldAttributes);\n";
		$this->import( $database, $sql );

		$newAttributeGroupIds = array();  // indexed by [group]
		$newAttributeIds = array();       // indexed by [group[[name]
		foreach ($attributes as $attribute) {
			$productId = $attribute['product_id'];
			$group = $attribute['attribute_group'];
			$name = $attribute['attribute_name'];
			$text = $attribute['text'];
			$attributeId = isset($attributeIds[$group][$name]) ? $attributeIds[$group][$name] : 0;
			$sql  = "INSERT INTO `@@product_attribute` (`product_id`,`attribute_id`,`language_id`,`text`) VALUES ";
			$sql .= "($productId, $attributeId, $languageId,'".$database->escape( $text )."');";
			$database->query( $sql );
		}

		$database->query("COMMIT;");
		return TRUE;
	}

	function storeSpecialsIntoDatabase( &$database, &$specials )
	{
		//delete old specials
		$oldSpecials = array();
		foreach ($specials as $special) {
			$oldSpecials[] = $special['product_id'];
		}
		$oldSpecials = implode(',', $oldSpecials);
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `@@product_special` WHERE product_id IN ($oldSpecials);\n";
		$this->import( $database, $sql );

		// find existing customer groups from the database
		$sql = "SELECT * FROM `@@customer_group`";
		$result = $database->query( $sql );
		$maxCustomerGroupId = 0;
		$customerGroups = array();
		foreach ($result->rows as $row) {
			$customerGroupId = $row['customer_group_id'];
			$name = $row['name'];
			if (!isset($customerGroups[$name])) {
				$customerGroups[$name] = $customerGroupId;
			}
			if ($maxCustomerGroupId < $customerGroupId) {
				$maxCustomerGroupId = $customerGroupId;
			}
		}

		// add additional customer groups into the database
		foreach ($specials as $special) {
			$name = $special['customer_group'];
			if (!isset($customerGroups[$name])) {
				$maxCustomerGroupId += 1;
				$sql  = "INSERT INTO `@@customer_group` (`customer_group_id`, `name`) VALUES ";
				$sql .= "($maxCustomerGroupId, '$name')";
				$sql .= ";\n";
				$database->query($sql);
				$customerGroups[$name] = $maxCustomerGroupId;
			}
		}

		// store product specials into the database
		$first = TRUE;
		$sql = "INSERT INTO `@@product_special` (`product_special_id`,`product_id`,`customer_group_id`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
		foreach ($specials as $special) {
			$productId = $special['product_id'];
			$name = $special['customer_group'];
			$customerGroupId = $customerGroups[$name];
			$priority = $special['priority'];
			$price = $special['price'];
			$dateStart = $special['date_start'];
			$dateEnd = $special['date_end'];
			$sql .= ($first) ? "\n" : ",\n";
			$first = FALSE;
			$sql .= "(NULL,$productId,$customerGroupId,$priority,$price,'$dateStart','$dateEnd')";
		}
		if (!$first) {
			$database->query($sql);
		}

		$database->query("COMMIT;");
		return TRUE;
	}

	function storeDiscountsIntoDatabase( &$database, &$discounts )
	{
		$oldDiscounts = array();
		foreach ($discounts as $discount) {
			$oldDiscounts[] = $discount['product_id'];
		}
		$oldDiscounts = implode(',', $oldDiscounts);
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `@@product_discount` WHERE product_id IN ($oldDiscounts);\n";
		$this->import( $database, $sql );

		// find existing customer groups from the database
		$sql = "SELECT * FROM `@@customer_group`";
		$result = $database->query( $sql );
		$maxCustomerGroupId = 0;
		$customerGroups = array();
		foreach ($result->rows as $row) {
			$customerGroupId = $row['customer_group_id'];
			$name = $row['name'];
			if (!isset($customerGroups[$name])) {
				$customerGroups[$name] = $customerGroupId;
			}
			if ($maxCustomerGroupId < $customerGroupId) {
				$maxCustomerGroupId = $customerGroupId;
			}
		}

		// add additional customer groups into the database
		foreach ($discounts as $discount) {
			$name = $discount['customer_group'];
			if (!isset($customerGroups[$name])) {
				$maxCustomerGroupId += 1;
				$sql  = "INSERT INTO `@@customer_group` (`customer_group_id`, `name`) VALUES ";
				$sql .= "($maxCustomerGroupId, '$name')";
				$sql .= ";\n";
				$database->query($sql);
				$customerGroups[$name] = $maxCustomerGroupId;
			}
		}

		// store product discounts into the database
		$first = TRUE;
		$sql = "INSERT INTO `@@product_discount` (`product_discount_id`,`product_id`,`customer_group_id`,`quantity`,`priority`,`price`,`date_start`,`date_end` ) VALUES ";
		foreach ($discounts as $discount) {
			$productId = $discount['product_id'];
			$name = $discount['customer_group'];
			$customerGroupId = $customerGroups[$name];
			$quantity = $discount['quantity'];
			$priority = $discount['priority'];
			$price = $discount['price'];
			$dateStart = $discount['date_start'];
			$dateEnd = $discount['date_end'];
			$sql .= ($first) ? "\n" : ",\n";
			$first = FALSE;
			$sql .= "(NULL,$productId,$customerGroupId,$quantity,$priority,$price,'$dateStart','$dateEnd')";
		}
		if (!$first) {
			$database->query($sql);
		}

		$database->query("COMMIT;");
		return TRUE;
	}

	function storeRewardsIntoDatabase( &$database, &$rewards )
	{
		$oldRewards = array();
		foreach ($rewards as $reward) {
			$oldRewards[] = $reward['product_id'];
		}
		$oldRewards = implode(',', $oldRewards);
		$sql = "START TRANSACTION;\n";
		$sql .= "DELETE FROM `@@product_reward` WHERE product_id IN ($oldRewards);\n";
		$this->import( $database, $sql );

		// find existing customer groups from the database
		$sql = "SELECT * FROM `@@customer_group`";
		$result = $database->query( $sql );
		$maxCustomerGroupId = 0;
		$customerGroups = array();
		foreach ($result->rows as $row) {
			$customerGroupId = $row['customer_group_id'];
			$name = $row['name'];
			if (!isset($customerGroups[$name])) {
				$customerGroups[$name] = $customerGroupId;
			}
			if ($maxCustomerGroupId < $customerGroupId) {
				$maxCustomerGroupId = $customerGroupId;
			}
		}

		// add additional customer groups into the database
		foreach ($rewards as $reward) {
			$name = $reward['customer_group'];
			if (!isset($customerGroups[$name])) {
				$maxCustomerGroupId += 1;
				$sql  = "INSERT INTO `@@customer_group` (`customer_group_id`, `name`) VALUES ";
				$sql .= "($maxCustomerGroupId, '$name')";
				$sql .= ";\n";
				$database->query($sql);
				$customerGroups[$name] = $maxCustomerGroupId;
			}
		}

		// store product rewards into the database
		$first = TRUE;
		$sql = "INSERT INTO `@@product_reward` (`product_reward_id`,`product_id`,`customer_group_id`,`points` ) VALUES ";
		foreach ($rewards as $reward) {
			$productId = $reward['product_id'];
			$name = $reward['customer_group'];
			$customerGroupId = $customerGroups[$name];
			$points = $reward['points'];
			$sql .= ($first) ? "\n" : ",\n";
			$first = FALSE;
			$sql .= "(NULL,$productId,$customerGroupId,$points)";
		}
		if (!$first) {
			$database->query($sql);
		}

		$database->query("COMMIT;");
		return TRUE;
	}

	function getCell(&$worksheet, $row, $col, $default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}

	function validateHeading( &$data, &$expected ) {
		$heading = array();
		$k = PHPExcel_Cell::columnIndexFromString( $data->getHighestColumn() );
		// if ($k != count($expected)) {
			// return FALSE;
		// }
		$i = 0;
		for ($j=1; $j <= $k; $j+=1) {
			$column = $this->getCell($data,$i,$j);
			if ($column == 'product_id') {
				$this->import_mode = "update";
			}
			$heading[] = $column;
		}
		$valid = TRUE;

		for ($i=0; $i < $k; $i+=1) {
			if (!in_array($heading[$i], $expected)) {
				$valid = FALSE;
				break;
			}
		}

		// for ($i=0; $i < count($expected); $i+=1) {
			// if (!isset($heading[$i])) {
				// $valid = FALSE;
				// break;
			// }
			// if (strtolower($heading[$i]) != strtolower($expected[$i])) {
				// $valid = FALSE;
				// break;
			// }
		// }
		return $valid;
	}

	function validateProducts( &$reader )
	{
		$expectedProductHeading = array
		('product_id', 'model', 'cate_id' ,'name', 'categories', 'manufacturer', 'supplier', 'sku', 'upc', 'ean','jan', 'isbn', 'mpn', 'location', 'quantity', 'shipping', 'msrp' ,'cost', 'price', 'points', 'date_available', 'weight', 'weight_class', 'length', 'width', 'height', 'length_class', 'status', 'tax_class_id', 'stock_status_id', 'sort_order', 'subtract', 'minimum', 'image', 'additional_images', 'tag', 'seo_url', 'viewed', 'reviews', 'rating', 'link', 'source_link', 'description', 'summary', 'seo_title' , 'meta_description', 'meta_keyword');
		$data =& $reader->getSheetByName('Products');
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedProductHeading );
		} else {
			return false;
		}
	}


	function validateOptions( &$reader )
	{
		$expectedOptionHeading = array
		('product_id', 'model', 'option', 'type', 'value', 'required', 'quantity', 'subtract', 'price', 'price_prefix', 'points', 'points_prefix', 'weight', 'weight_prefix');
		$data =& $reader->getSheetByName("Options");
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedOptionHeading );
		}
		return true;
	}


	function validateAttributes( &$reader )
	{
		$expectedAttributeHeading = array
		( "product_id", "model", "attribute_group", "attribute_name", "text");
		$data =& $reader->getSheetByName("Attributes");
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedAttributeHeading );
		}
		return true;
	}


	function validateSpecials( &$reader )
	{
		$expectedSpecialsHeading = array
		( "product_id", "model", "customer_group", "priority", "price", "date_start", "date_end" );
		$data =& $reader->getSheetByName("Specials");
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedSpecialsHeading );
		}
		return true;
	}


	function validateDiscounts( &$reader )
	{
		$expectedDiscountsHeading = array
		( "product_id", "model", "customer_group", "quantity", "priority", "price", "date_start", "date_end" );
		$data =& $reader->getSheetByName("Discounts");
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedDiscountsHeading );
		}
		return true;
	}


	function validateRewards( &$reader )
	{
		$expectedRewardsHeading = array
		( "product_id", "model", "customer_group", "points" );
		$data =& $reader->getSheetByName("Rewards");
		if ($data !== null) {
			return $this->validateHeading( $data, $expectedRewardsHeading );
		}
		return true;
	}


	function validateUpload( &$reader )
	{

		if (!$this->validateProducts( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_products_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		if (!$this->validateOptions( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_options_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		if (!$this->validateAttributes( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_attributes_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		if (!$this->validateSpecials( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_specials_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		if (!$this->validateDiscounts( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_discounts_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		if (!$this->validateRewards( $reader )) {
			error_log(date('Y-m-d H:i:s - ', time()).L('error_rewards_header')."\n",3,DIR_LOGS."error.txt");
			return FALSE;
		}
		return TRUE;
	}


	function clearCache() {
		$this->cache->delete('category');
		$this->cache->delete('category_description');
		$this->cache->delete('manufacturer');
		$this->cache->delete('product');
		$this->cache->delete('product_image');
		$this->cache->delete('product_option');
		$this->cache->delete('product_option_description');
		$this->cache->delete('product_option_value');
		$this->cache->delete('product_option_value_description');
		$this->cache->delete('product_to_category');
		//$this->cache->delete('url_alias');
		$this->cache->delete('product_special');
		$this->cache->delete('product_discount');
	}


	function upload( $filename ) {
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		$database =& $this->db;
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		//set_time_limit( 60 );
		chdir( '../system/PHPExcel' );
		require_once( 'Classes/PHPExcel.php' );
		chdir( '../../admin' );
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$ok = $this->validateUpload( $reader );
		if (!$ok) {
			return FALSE;
		}
		$this->clearCache();

		$ok = $this->uploadProducts( $reader, $database );
		if (!$ok) {
			return FALSE;
		}
		
		$options = array();
		$this->getProductAdditionalData($reader, $options, "Options");
		if (!empty($options)) {
			$this->storeOptionsIntoDatabase( $database, $options);
		}
		
		$attributes = array();
		$this->getProductAdditionalData($reader, $attributes, "Attributes");
		if (!empty($attributes)) {
			$this->storeAttributesIntoDatabase( $database, $attributes);
		}		
		
		$specials = array();
		$this->getProductAdditionalData($reader, $specials, "Specials");
		if (!empty($specials)) {
			$this->storeSpecialsIntoDatabase( $database, $specials);
		}
				
		$discounts = array();
		$this->getProductAdditionalData($reader, $discounts, "Discounts");
		if (!empty($discounts)) {
			$this->storeDiscountsIntoDatabase( $database, $discounts);
		}		
		
		$rewards = array();
		$this->getProductAdditionalData($reader, $rewards, "Rewards");
		if (!empty($rewards)) {
			$this->storeRewardsIntoDatabase( $database, $rewards);
		}
		chdir( '../../..' );
		return TRUE;
	}

	function getProductAdditionalData(&$reader, &$datas, $sheetName) {
		$data = $reader->getSheetByName($sheetName);
		if ($data == null) return;
		$row = array();
		$heading = array();
		$columnCount = PHPExcel_Cell::columnIndexFromString( $data->getHighestColumn() );
		for ($j=1; $j <= $columnCount; $j+=1) {
			$heading[] = $this->getCell($data, 0, $j);
		}
		$k = $data->getHighestRow();
		for ($i=1; $i < $k; $i+=1) {
			for ($j = 1; $j <= $columnCount; $j++) {
				$val = $this->getCell($data, $i, $j);
				if ($heading[$j-1] == 'model') {
					$row['product_id'] = $this->productModelIds[$val];					
				}
				else {
					$row[$heading[$j-1]] = $val;
				}
			}
			$datas[] = $row;
		}
	}

	function getStoreIdsForCategories( &$database ) {
		$sql =  "SELECT category_id, store_id FROM `@@category_to_store` cs;";
		$storeIds = array();
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$categoryId = $row['category_id'];
			$storeId = $row['store_id'];
			if (!isset($storeIds[$categoryId])) {
				$storeIds[$categoryId] = array();
			}
			if (!in_array($storeId,$storeIds[$categoryId])) {
				$storeIds[$categoryId][] = $storeId;
			}
		}
		return $storeIds;
	}


	function getLayoutsForCategories( &$database ) {
		$sql  = "SELECT cl.*, l.name FROM `@@category_to_layout` cl ";
		$sql .= "LEFT JOIN `@@layout` l ON cl.layout_id = l.layout_id ";
		$sql .= "ORDER BY cl.category_id, cl.store_id;";
		$result = $database->query( $sql );
		$layouts = array();
		foreach ($result->rows as $row) {
			$categoryId = $row['category_id'];
			$storeId = $row['store_id'];
			$name = $row['name'];
			if (!isset($layouts[$categoryId])) {
				$layouts[$categoryId] = array();
			}
			$layouts[$categoryId][$storeId] = $name;
		}
		return $layouts;
	}


	function populateCategoriesWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, &$textFormat, $category_ids = null)
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('category_id')+1);
		$worksheet->setColumn($j,$j++,strlen('parent_id')+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('top'),5)+1);
		$worksheet->setColumn($j,$j++,strlen('columns')+1);
		$worksheet->setColumn($j,$j++,strlen('sort_order')+1);
		$worksheet->setColumn($j,$j++,max(strlen('image_name'),12)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('language_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_url'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('seo_title'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta_description'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('meta_keyword'),32)+1);
		$worksheet->setColumn($j,$j++,max(strlen('store_ids'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('layout'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('status'),5)+1,$textFormat);

		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'category_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'parent_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'top', $boxFormat );
		$worksheet->writeString( $i, $j++, 'columns', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image_name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'language_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_url', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_title', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta_description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'store_ids', $boxFormat );
		$worksheet->writeString( $i, $j++, 'layout', $boxFormat );
		$worksheet->writeString( $i, $j++, "status\nenabled", $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual categories data
		$i += 1;
		$j = 0;
		$storeIds = $this->getStoreIdsForCategories( $database );
		$layouts = $this->getLayoutsForCategories( $database );
		$query  = "SELECT c.* , cd.* FROM `@@category` c ";
		$query .= "INNER JOIN `@@category_description` cd ON cd.category_id = c.category_id ";
		$query .= " AND cd.language_id=$languageId ";
		if ($category_ids) {
			$query .= "WHERE c.category_id IN ($category_ids)";
		}
		$query .= "ORDER BY c.`parent_id`, `sort_order`, c.`category_id`;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 26 );
			$worksheet->write( $i, $j++, $row['category_id'] );
			$worksheet->write( $i, $j++, $row['parent_id'] );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, ($row['top']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['column'] );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, $row['image'] );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['language_id'] );
			$worksheet->writeString( $i, $j++, $row['seo_url']);
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['seo_title'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8') );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['meta_keyword'],ENT_QUOTES,'UTF-8') );
			$storeIdList = '';
			$categoryId = $row['category_id'];
			if (isset($storeIds[$categoryId])) {
				foreach ($storeIds[$categoryId] as $storeId) {
					$storeIdList .= ($storeIdList=='') ? $storeId : ','.$storeId;
				}
			}
			$worksheet->write( $i, $j++, $storeIdList, $textFormat );
			$layoutList = '';
			if (isset($layouts[$categoryId])) {
				foreach ($layouts[$categoryId] as $storeId => $name) {
					$layoutList .= ($layoutList=='') ? $storeId.':'.$name : ','.$storeId.':'.$name;
				}
			}
			$worksheet->write( $i, $j++, $layoutList, $textFormat );
			$worksheet->write( $i, $j++, ($row['status']==0) ? "false" : "true", $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function getStoreIdsForProducts( &$database ) {
		$sql =  "SELECT product_id, store_id FROM `@@product_to_store` ps;";
		$storeIds = array();
		$result = $database->query( $sql );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$storeId = $row['store_id'];
			if (!isset($storeIds[$productId])) {
				$storeIds[$productId] = array();
			}
			if (!in_array($storeId,$storeIds[$productId])) {
				$storeIds[$productId][] = $storeId;
			}
		}
		return $storeIds;
	}


	function getLayoutsForProducts( &$database ) {
		$sql  = "SELECT pl.*, l.name FROM `@@product_to_layout` pl ";
		$sql .= "LEFT JOIN `@@layout` l ON pl.layout_id = l.layout_id ";
		$sql .= "ORDER BY pl.product_id, pl.store_id;";
		$result = $database->query( $sql );
		$layouts = array();
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$storeId = $row['store_id'];
			$name = $row['name'];
			if (!isset($layouts[$productId])) {
				$layouts[$productId] = array();
			}
			$layouts[$productId][$storeId] = $name;
		}
		return $layouts;
	}


	function populateProductsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, &$textFormat, $category_ids = null)
	{


		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('model'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('name'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('categories'),12)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('manufacturer'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('supplier'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('sku'),10)+1);
		//$worksheet->setColumn($j,$j++,max(strlen('upc'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('location'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('shipping'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('msrp'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('cost'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('points'),5)+1);
		$worksheet->setColumn($j,$j++,max(strlen('date_available'),10)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),6)+1,$weightFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight_class'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('width'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('height'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('length_class'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('status'),5)+1,$textFormat);
		//$worksheet->setColumn($j,$j++,max(strlen('tax_class_id'),2)+1);
		$worksheet->setColumn($j,$j++,max(strlen('stock_status_id'),3)+1);
		$worksheet->setColumn($j,$j++,max(strlen('sort_order'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('subtract'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('minimum'),8)+1);
		$worksheet->setColumn($j,$j++,max(strlen('image'),12)+1);;
		$worksheet->setColumn($j,$j++,max(strlen('additional_images'),24)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('tag'),32)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('seo_url'),16)+1);
		$worksheet->setColumn($j,$j++,max(strlen('description'),32)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('seo_title'),32)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('meta_description'),32)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('meta_keyword'),32)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('related_ids'),16)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_added'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_modified'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('viewed'),5)+1);


		// The product headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'model', $boxFormat );
		$worksheet->writeString( $i, $j++, 'name', $boxFormat );
		$worksheet->writeString( $i, $j++, 'categories', $boxFormat );
		$worksheet->writeString( $i, $j++, 'manufacturer', $boxFormat );
		$worksheet->writeString( $i, $j++, 'supplier', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sku', $boxFormat );
		//$worksheet->writeString( $i, $j++, 'upc', $boxFormat );
		$worksheet->writeString( $i, $j++, 'location', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
		$worksheet->writeString( $i, $j++, "shipping", $boxFormat );
		$worksheet->writeString( $i, $j++, 'msrp', $boxFormat );
		$worksheet->writeString( $i, $j++, 'cost', $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'points', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_available', $boxFormat );
		$worksheet->writeString( $i, $j++, 'weight', $boxFormat );
		$worksheet->writeString( $i, $j++, 'weight_class', $boxFormat );
		$worksheet->writeString( $i, $j++, 'length', $boxFormat );
		$worksheet->writeString( $i, $j++, 'width', $boxFormat );
		$worksheet->writeString( $i, $j++, 'height', $boxFormat );
		$worksheet->writeString( $i, $j++, "length_class", $boxFormat );
		$worksheet->writeString( $i, $j++, "status", $boxFormat );
		//$worksheet->writeString( $i, $j++, 'tax_class_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'stock_status_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'sort_order', $boxFormat );
		$worksheet->writeString( $i, $j++, "subtract", $boxFormat );
		$worksheet->writeString( $i, $j++, 'minimum', $boxFormat );
		$worksheet->writeString( $i, $j++, 'image', $boxFormat );
		$worksheet->writeString( $i, $j++, 'additional_images', $boxFormat );
		$worksheet->writeString( $i, $j++, 'tag', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_url', $boxFormat );
		$worksheet->writeString( $i, $j++, 'description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'seo_title', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta_description', $boxFormat );
		$worksheet->writeString( $i, $j++, 'meta_keyword', $boxFormat );
		$worksheet->writeString( $i, $j++, 'related_ids', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_added', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_modified', $boxFormat );
		$worksheet->writeString( $i, $j++, 'viewed', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );

		// Get all additional product images
		$imageNames = array();
		$query  = "SELECT DISTINCT ";
		$query .= "  p.product_id, ";
		$query .= "  pi.product_image_id AS image_id, ";
		$query .= "  pi.image AS filename ";
		$query .= "FROM `@@product` p ";
		$query .= "INNER JOIN `@@product_image` pi ON pi.product_id=p.product_id ";
		if ($category_ids) {
			$query .= " WHERE p.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids)) ";
		}
		$query .= "ORDER BY product_id, image_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$productId = $row['product_id'];
			$imageId = $row['image_id'];
			$imageName = $row['filename'];
			if (!isset($imageNames[$productId])) {
				$imageNames[$productId] = array();
				$imageNames[$productId][$imageId] = $imageName;
			}
			else {
				$imageNames[$productId][$imageId] = $imageName;
			}
		}

		// The actual products data
		$i += 1;
		$j = 0;
		$storeIds = $this->getStoreIdsForProducts( $database );
		$layouts = $this->getLayoutsForProducts( $database );
		$query  = "SELECT ";
		$query .= "  p.product_id,";
		$query .= "  pd.name,";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pc.category_id AS CHAR(11)) SEPARATOR \",\" ) AS categories,";
		$query .= "  p.sku,";		
		$query .= "  p.location,";
		$query .= "  p.quantity,";
		$query .= "  p.model,";
		$query .= "  m.name AS manufacturer,";
		$query .= "  s.name AS supplier,";
		$query .= "  p.image AS image_name,";
		$query .= "  p.shipping,";
		$query .= "  p.msrp,";
		$query .= "  p.cost,";
		$query .= "  p.price,";
		$query .= "  p.points,";
		$query .= "  p.date_added,";
		$query .= "  p.date_modified,";
		$query .= "  p.date_available,";
		$query .= "  p.weight,";
		$query .= "  wc.unit,";
		$query .= "  p.length,";
		$query .= "  p.width,";
		$query .= "  p.height,";
		$query .= "  p.status,";
		//$query .= "  p.tax_class_id,";
		$query .= "  p.viewed,";
		$query .= "  p.sort_order,";
		$query .= "  pd.language_id,";
		$query .= "  p.seo_url,";
		$query .= "  pd.description, ";
		$query .= "  pd.seo_title, ";
		$query .= "  pd.meta_description, ";
		$query .= "  pd.meta_keyword, ";
		$query .= "  pd.tag, ";
		$query .= "  p.stock_status_id, ";
		$query .= "  mc.unit AS length_unit, ";
		$query .= "  p.subtract, ";
		$query .= "  p.minimum, ";
		$query .= "  GROUP_CONCAT( DISTINCT CAST(pr.related_id AS CHAR(11)) SEPARATOR \",\" ) AS related ";
		$query .= "FROM `@@product` p ";
		$query .= "LEFT JOIN `@@product_description` pd ON p.product_id=pd.product_id ";
		$query .= "  AND pd.language_id=$languageId ";
		$query .= "LEFT JOIN `@@product_to_category` pc ON p.product_id=pc.product_id ";
		$query .= "LEFT JOIN `@@manufacturer` m ON m.manufacturer_id = p.manufacturer_id ";
		$query .= "LEFT JOIN `@@supplier` s ON s.supplier_id = p.supplier_id ";
		$query .= "LEFT JOIN `@@weight_class_description` wc ON wc.weight_class_id = p.weight_class_id ";
		$query .= "  AND wc.language_id=$languageId ";
		$query .= "LEFT JOIN `@@length_class_description` mc ON mc.length_class_id=p.length_class_id ";
		$query .= "  AND mc.language_id=$languageId ";
		$query .= "LEFT JOIN `@@product_related` pr ON pr.product_id=p.product_id ";
		if ($category_ids) {
			$query .= " WHERE pc.category_id IN ($category_ids)";
		}
		$query .= "GROUP BY p.product_id ";
		$query .= "ORDER BY p.product_id, pc.category_id; ";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 26 );
			$productId = $row['product_id'];
			$worksheet->write( $i, $j++, $productId );
			$worksheet->writeString( $i, $j++, $row['model'] );
			$worksheet->writeString( $i, $j++, html_entity_decode($row['name'],ENT_QUOTES,'UTF-8') );
			$worksheet->write( $i, $j++, $row['categories'], $textFormat );
			$worksheet->writeString( $i, $j++, $row['manufacturer'] );
			$worksheet->writeString( $i, $j++, $row['supplier'] );
			$worksheet->writeString( $i, $j++, $row['sku'] );			
			$worksheet->writeString( $i, $j++, $row['location'] );
			$worksheet->write( $i, $j++, $row['quantity'] );
			$worksheet->write( $i, $j++, ($row['shipping']==0) ? "no" : "yes", $textFormat );
			$worksheet->write( $i, $j++, $row['msrp'] , $priceFormat);
			$worksheet->write( $i, $j++, $row['cost'], $priceFormat );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['points'] );
			$worksheet->write( $i, $j++, $row['date_available'], $textFormat );
			$worksheet->write( $i, $j++, $row['weight'], $weightFormat );
			$worksheet->writeString( $i, $j++, $row['unit'] );
			$worksheet->write( $i, $j++, $row['length'] );
			$worksheet->write( $i, $j++, $row['width'] );
			$worksheet->write( $i, $j++, $row['height'] );
			$worksheet->writeString( $i, $j++, $row['length_unit'] );
			$worksheet->write( $i, $j++, ($row['status']==0) ? "false" : "true", $textFormat );
			//$worksheet->write( $i, $j++, $row['tax_class_id'] );
			$worksheet->write( $i, $j++, $row['stock_status_id'] );
			$worksheet->write( $i, $j++, $row['sort_order'] );
			$worksheet->write( $i, $j++, ($row['subtract']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['minimum'] );
			$worksheet->writeString( $i, $j++, $row['image_name'] );
			$names = "";
			if (isset($imageNames[$productId])) {
				$first = TRUE;
				foreach ($imageNames[$productId] AS $name) {
					if (!$first) {
						$names .= ",\n";
					}
					$first = FALSE;
					$names .= $name;
				}
			}
			$worksheet->write( $i, $j++, $names, $textFormat );
			$worksheet->write( $i, $j++, $row['tag'], $textFormat );
			$worksheet->writeString( $i, $j++, $row['seo_url']);
			$worksheet->writeString( $i, $j++, html_entity_decode($row['description'],ENT_QUOTES,'UTF-8'), $textFormat, TRUE );
			$worksheet->write( $i, $j++, html_entity_decode($row['seo_title'],ENT_QUOTES,'UTF-8'), $textFormat );
			$worksheet->write( $i, $j++, html_entity_decode($row['meta_description'],ENT_QUOTES,'UTF-8'), $textFormat );
			$worksheet->write( $i, $j++, html_entity_decode($row['meta_keyword'],ENT_QUOTES,'UTF-8'), $textFormat );
			$worksheet->write( $i, $j++, $row['related'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_added'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_modified'], $textFormat );
			$worksheet->write( $i, $j++, $row['viewed'] );

			$i += 1;
			$j = 0;
		}
	}


	function populateOptionsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, &$weightFormat, $textFormat, $category_ids = null )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('option'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('type'),10)+1);
		$worksheet->setColumn($j,$j++,max(strlen('value'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('required'),5)+1);
		$worksheet->setColumn($j,$j++,max(strlen('quantity'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('subtract'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('price'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('points'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('points'),5)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('weight'),5)+1,$textFormat);

		// The options headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'option', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'type', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'value', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'required', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'subtract', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat  );
		$worksheet->writeString( $i, $j++, "price_prefix", $boxFormat  );
		$worksheet->writeString( $i, $j++, 'points', $boxFormat  );
		$worksheet->writeString( $i, $j++, "points_prefix", $boxFormat  );
		$worksheet->writeString( $i, $j++, 'weight', $boxFormat  );
		$worksheet->writeString( $i, $j++, "weight_prefix", $boxFormat  );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual options data
		$i += 1;
		$j = 0;
		$query  = "SELECT po.product_id,";
		$query .= "  po.option_id,";
		$query .= "  po.option_value AS default_value,";
		$query .= "  po.required,";
		$query .= "  pov.option_value_id,";
		$query .= "  pov.quantity,";
		$query .= "  pov.subtract,";
		$query .= "  pov.price,";
		$query .= "  pov.price_prefix,";
		$query .= "  pov.points,";
		$query .= "  pov.points_prefix,";
		$query .= "  pov.weight,";
		$query .= "  pov.weight_prefix,";
		$query .= "  ovd.name AS option_value,";
		$query .= "  ov.sort_order,";
		$query .= "  od.name AS option_name,";
		$query .= "  o.type ";
		$query .= "FROM `@@product_option` po ";
		$query .= "LEFT JOIN `@@option` o ON o.option_id=po.option_id ";
		$query .= "LEFT JOIN `@@product_option_value` pov ON pov.product_option_id = po.product_option_id ";
		$query .= "LEFT JOIN `@@option_value` ov ON ov.option_value_id=pov.option_value_id ";
		$query .= "LEFT JOIN `@@option_value_description` ovd ON ovd.option_value_id=ov.option_value_id AND ovd.language_id=$languageId ";
		$query .= "LEFT JOIN `@@option_description` od ON od.option_id=o.option_id AND od.language_id=$languageId ";
		if ($category_ids) {
			$query .= " WHERE po.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids)) ";
		}
		$query .= "ORDER BY po.product_id, po.option_id, pov.option_value_id;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 13 );
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->writeString( $i, $j++, $row['option_name'] );
			$worksheet->writeString( $i, $j++, $row['type'] );
			$worksheet->writeString( $i, $j++, ($row['default_value']) ? $row['default_value'] : $row['option_value'] );
			$worksheet->write( $i, $j++, ($row['required']==0) ? "false" : "true", $textFormat );
			$worksheet->write( $i, $j++, $row['quantity'] );
			if (is_null($row['option_value_id'])) {
				$subtract = '';
			} else {
				$subtract = ($row['subtract']==0) ? "false" : "true";
			}
			$worksheet->write( $i, $j++, $subtract, $textFormat );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->writeString( $i, $j++, $row['price_prefix'], $textFormat );
			$worksheet->write( $i, $j++, $row['points'] );
			$worksheet->writeString( $i, $j++, $row['points_prefix'], $textFormat );
			$worksheet->write( $i, $j++, $row['weight'], $weightFormat );
			$worksheet->writeString( $i, $j++, $row['weight_prefix'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function populateAttributesWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, $textFormat, $category_ids = null )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,max(strlen('product_id'),4)+1);
		$worksheet->setColumn($j,$j++,max(strlen('attribute_group'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('attribute_name'),30)+1);
		$worksheet->setColumn($j,$j++,max(strlen('text'),30)+1);

		// The attributes headings row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'attribute_group', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'attribute_name', $boxFormat  );
		$worksheet->writeString( $i, $j++, 'text', $boxFormat  );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual attributes data
		$i += 1;
		$j = 0;
		$query  = "SELECT pa.*, a.attribute_group_id, ad.name AS attribute_name, a.sort_order, agd.name AS attribute_group ";
		$query .= "FROM `@@product_attribute` pa ";
		$query .= "LEFT JOIN `@@attribute` a ON a.attribute_id=pa.attribute_id ";
		$query .= "LEFT JOIN `@@attribute_description` ad ON ad.attribute_id=a.attribute_id AND ad.language_id=$languageId ";
		$query .= "LEFT JOIN `@@attribute_group_description` agd ON agd.attribute_group_id=a.attribute_group_id AND agd.language_id=$languageId ";
		$query .= "WHERE pa.language_id=$languageId ";
		if ($category_ids) {
			$query .= " AND pa.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids)) ";
		}
		$query .= "ORDER BY pa.product_id, a.attribute_group_id, a.attribute_id;";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 13 );
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->writeString( $i, $j++, $row['attribute_group'] );
			$worksheet->writeString( $i, $j++, $row['attribute_name'] );
			$worksheet->writeString( $i, $j++, $row['text'] );
			$i += 1;
			$j = 0;
		}
	}


	function populateSpecialsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, &$textFormat, $category_ids = null )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('product_id')+1);
		$worksheet->setColumn($j,$j++,strlen('customer_group')+1);
		$worksheet->setColumn($j,$j++,strlen('priority')+1);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_start'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_end'),19)+1,$textFormat);

		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'customer_group', $boxFormat );
		$worksheet->writeString( $i, $j++, 'priority', $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_start', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_end', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual product specials data
		$i += 1;
		$j = 0;
		$query  = "SELECT ps.*, cg.name FROM `@@product_special` ps ";
		$query .= "LEFT JOIN `@@customer_group_description` cg ON cg.customer_group_id=ps.customer_group_id WHERE cg.language_id=$languageId";
		if ($category_ids) {
			$query .= " AND ps.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids)) ";
		}
		$query .= "ORDER BY ps.product_id, cg.name";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 13 );
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $row['name'] );
			$worksheet->write( $i, $j++, $row['priority'] );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_start'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_end'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function populateDiscountsWorksheet( &$worksheet, &$database, $languageId, &$priceFormat, &$boxFormat, &$textFormat, $category_ids = null )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('product_id')+1);
		$worksheet->setColumn($j,$j++,strlen('customer_group')+1);
		$worksheet->setColumn($j,$j++,strlen('quantity')+1);
		$worksheet->setColumn($j,$j++,strlen('priority')+1);
		$worksheet->setColumn($j,$j++,max(strlen('price'),10)+1,$priceFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_start'),19)+1,$textFormat);
		$worksheet->setColumn($j,$j++,max(strlen('date_end'),19)+1,$textFormat);

		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'customer_group', $boxFormat );
		$worksheet->writeString( $i, $j++, 'quantity', $boxFormat );
		$worksheet->writeString( $i, $j++, 'priority', $boxFormat );
		$worksheet->writeString( $i, $j++, 'price', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_start', $boxFormat );
		$worksheet->writeString( $i, $j++, 'date_end', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual product discounts data
		$i += 1;
		$j = 0;
		$query  = "SELECT pd.*, cg.name FROM `@@product_discount` pd ";
		$query .= "LEFT JOIN `@@customer_group_description` cg ON cg.customer_group_id=pd.customer_group_id WHERE language_id=$languageId";
		if ($category_ids) {
			$query .= " AND pd.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids))";
		}
		$query .= "ORDER BY pd.product_id, cg.name";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 13 );
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $row['name'] );
			$worksheet->write( $i, $j++, $row['quantity'] );
			$worksheet->write( $i, $j++, $row['priority'] );
			$worksheet->write( $i, $j++, $row['price'], $priceFormat );
			$worksheet->write( $i, $j++, $row['date_start'], $textFormat );
			$worksheet->write( $i, $j++, $row['date_end'], $textFormat );
			$i += 1;
			$j = 0;
		}
	}


	function populateRewardsWorksheet( &$worksheet, &$database, $languageId, &$boxFormat, $category_ids = null )
	{
		// Set the column widths
		$j = 0;
		$worksheet->setColumn($j,$j++,strlen('product_id')+1);
		$worksheet->setColumn($j,$j++,strlen('customer_group')+1);
		$worksheet->setColumn($j,$j++,strlen('points')+1);

		// The heading row
		$i = 0;
		$j = 0;
		$worksheet->writeString( $i, $j++, 'product_id', $boxFormat );
		$worksheet->writeString( $i, $j++, 'customer_group', $boxFormat );
		$worksheet->writeString( $i, $j++, 'points', $boxFormat );
		$worksheet->setRow( $i, 30, $boxFormat );

		// The actual product discounts data
		$i += 1;
		$j = 0;
		$query  = "SELECT pr.*, cg.name FROM `@@product_reward` pr ";
		$query .= "LEFT JOIN `@@customer_group_description` cg ON cg.customer_group_id=pr.customer_group_id WHERE language_id=$languageId";
		if ($category_ids) {
			$query .= " AND pr.product_id IN (SELECT product_id FROM `@@product_to_category` WHERE category_id IN ($category_ids))";
		}
		$query .= "ORDER BY pr.product_id, cg.name";
		$result = $database->query( $query );
		foreach ($result->rows as $row) {
			$worksheet->setRow( $i, 13 );
			$worksheet->write( $i, $j++, $row['product_id'] );
			$worksheet->write( $i, $j++, $row['name'] );
			$worksheet->write( $i, $j++, $row['points'] );
			$i += 1;
			$j = 0;
		}
	}


	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}


	function download($options = array()) {
		global $config;
		global $log;

		$product_categories = $options['product_category'];
		$product_categories = implode(',', $product_categories);
		$option_categories = isset($options['option_categories']) ? 1 : 0;
		$option_products = isset($options['option_products']) ? 1 : 0;
		$option_attributes = isset($options['option_attributes']) ? 1 : 0;
		$option_options = isset($options['option_options']) ? 1 : 0;
		$option_specials = isset($options['option_specials']) ? 1 : 0;
		$option_discounts = isset($options['option_discounts']) ? 1 : 0;
		$option_rewards = isset($options['option_rewards']) ? 1 : 0;

		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_export');
		$database =& $this->db;
		$languageId = $this->getDefaultLanguageId($database);

		// We use the package from http://pear.php.net/package/Spreadsheet_Excel_Writer/
		chdir( '../system/pear' );
		require_once "Spreadsheet/Excel/Writer.php";
		chdir( '../../admin' );

		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format
		$priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));

		// sending HTTP headers
		$workbook->send('export_categories_products.xls');

		// Creating the categories worksheet
		if ($option_categories) {
			$worksheet =& $workbook->addWorksheet('Categories');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateCategoriesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_products) {
			// Creating the products worksheet
			$worksheet =& $workbook->addWorksheet('Products');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateProductsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_options) {
			// Creating the options worksheet
			$worksheet =& $workbook->addWorksheet('Options');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateOptionsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $weightFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_attributes) {
			// Creating the attributes worksheet
			$worksheet =& $workbook->addWorksheet('Attributes');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateAttributesWorksheet( $worksheet, $database, $languageId, $boxFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_specials) {
			// Creating the specials worksheet
			$worksheet =& $workbook->addWorksheet('Specials');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateSpecialsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_discounts) {
			// Creating the discounts worksheet
			$worksheet =& $workbook->addWorksheet('Discounts');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateDiscountsWorksheet( $worksheet, $database, $languageId, $priceFormat, $boxFormat, $textFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}

		if ($option_rewards) {
			// Creating the rewards worksheet
			$worksheet =& $workbook->addWorksheet('Rewards');
			$worksheet->setInputEncoding ( 'UTF-8' );
			$this->populateRewardsWorksheet( $worksheet, $database, $languageId, $boxFormat, $product_categories);
			$worksheet->freezePanes(array(1, 1, 1, 1));
		}
		// Let's send the file
		$workbook->close();

		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
		exit;
	}


}
?>
<?php
/********************************************
	PayPal Express API Module 
********************************************/
class ModelPaymentPaypalexpresscurl extends Model {
	
	private $USE_PROXY = false;
	private $PROXY_HOST = '127.0.0.1';
	private $PROXY_PORT = '808';

	//'------------------------------------
	//' PayPal API Credentials
	//' Replace <API_USERNAME> with your API Username
	//' Replace <API_PASSWORD> with your API Password
	//' Replace <API_SIGNATURE> with your Signature
	//'------------------------------------
	
	private $API_UserName;
	private $API_Password;
	private $API_Signature;
	
	// BN Code 	is only applicable for partners
	private $sBNCode = "PP-ECWizard";
		
	private $version="64";	
	private $paymentType = 'Sale'; 
	
	private $ERROR = FALSE;
	
  	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->API_UserName = C('paypal_express_username');
		$this->API_Password = C('paypal_express_password');
		$this->API_Signature = C('paypal_express_signature');					
		
		if (C('paypal_express_test')) {
			$this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
		}
		else {
			$this->API_Endpoint = "https://api-3t.paypal.com/nvp";
			$this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
		}
		
		if(C('paypal_express_method')){
			$this->paymentType = "Sale";
		}
		else {
			$this->paymentType = "Authorization";
		}
	}
	
	public function getErrorMsg(&$resArray) {
		return $resArray["L_LONGMESSAGE0"];
	}
	
	protected function logErrorMsg(&$resArray, $methodName) {
		$data = array(
			"order_id" => $this->session->data['order_id'],
			"method" => $methodName,
			"error_code" => $resArray["L_ERRORCODE0"],
			"severity_code" => $resArray["L_SEVERITYCODE0"],
			"short_msg" => $resArray["L_SHORTMESSAGE0"],
			"long_msg" => $resArray["L_LONGMESSAGE0"],
			"date_added" => date("Y-m-d H:i:s")
		);
		$this->db->insert("paypal_express_error", $data);		
	}
	
	public function error() {
		return $this->ERROR;
	}
	
	public function SetExpressCheckout($invNum, $paymentAmount, $returnURL, $cancelURL, $address = array()) {
		$nvpArray = array(
			"PAYMENTREQUEST_0_AMT" => $paymentAmount,
			"PAYMENTREQUEST_0_PAYMENTACTION" => $this->paymentType,
			"RETURNURL" => $returnURL,
			"CANCELURL" => $cancelURL,
			"PAYMENTREQUEST_0_CURRENCYCODE" => $this->currency->getCode(),
			"PAYMENTREQUEST_0_INVNUM" => $invNum,			
			"ALLOWNOTE" => 1,
		);
		if (!empty($this->session->data['comment'])) {
			$nvpArray["PAYMENTREQUEST_0_NOTETEXT"] = $this->session->data['comment'];
		}
		if (!empty($address)) {
			$countryCode = $this->db->queryOne("SELECT iso_code_2 FROM @@country WHERE country_id = '" . $address["shipping_country_id"] . "'");
			$zoneCode = $this->db->queryOne("SELECT code FROM `@@zone` WHERE zone_id = '" . $address["shipping_zone_id"] . "'");
			//$nvpArray["REQCONFIRMSHIPPING"] = 1;
			$nvpArray["ADDROVERRIDE"] = 1;
			$nvpArray["PAYMENTREQUEST_0_SHIPTONAME"] = $address["shipping_firstname"] . ' ' . $address["shipping_lastname"];
			$nvpArray["PAYMENTREQUEST_0_SHIPTOSTREET"] = $address["shipping_address_1"];
			if (!empty($address["shipping_address_2"])){
				$nvpArray["PAYMENTREQUEST_0_SHIPTOSTREET2"] = $address["shipping_address_2"];
			}
			$nvpArray["PAYMENTREQUEST_0_SHIPTOCITY"] = $address["shipping_city"];
			$nvpArray["PAYMENTREQUEST_0_SHIPTOSTATE"] = $zoneCode;
			$nvpArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"] = $countryCode;
			$nvpArray["PAYMENTREQUEST_0_SHIPTOZIP"] = $address["shipping_postcode"];
			$nvpArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"] = $address["telephone"];
		}
        $resArray = $this->hash_call("SetExpressCheckout", $nvpArray);
		$ack = strtoupper($resArray["ACK"]);
		if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
			$this->session->data['PE_TOKEN'] = $resArray["TOKEN"];
		}
		else {
			$this->ERROR = TRUE;
			$this->logErrorMsg($resArray, "SetExpressCheckout");
		}
	    return $resArray;
	}
	
	public function getCheckoutDetails($token = null) {
		if (!$token && isset($this->session->data['PE_TOKEN'])) {
			$token = $this->session->data['PE_TOKEN'];
		}
	    $resArray = $this->hash_call("GetExpressCheckoutDetails", array("TOKEN" => $token));
		$ack = strtoupper($resArray["ACK"]);
		if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {	
			$this->session->data['PE_PAYER_ID'] =	$resArray["PAYERID"];
			$payerInfo = $this->getPayerInfo($resArray);
			$order_id = $this->session->data['order_id'];
			$payerInfo['order_id'] = $order_id;
			$id = $this->db->queryOne("SELECT id FROM @@paypal_express_payment WHERE order_id = '$order_id'");
			if ($id) {
				$this->db->update("paypal_express_payment", $payerInfo, "id=$id");
			}
			else {
				$payerInfo['date_added'] = date('Y-m-d H:i:s');
				$this->db->insert("paypal_express_payment", $payerInfo);
			}
			$this->db->runSql("UPDATE `@@order` SET payment_payer_status = '" . $payerInfo['payer_status'] . "' WHERE order_id = '$order_id'");
		}
		else {
			$this->ERROR = TRUE;
			$this->logErrorMsg($resArray, "GetExpressCheckoutDetails");
		}
		return $resArray;
	}
	
	//use for GetExpressCheckoutDetails
	public function getShippingAddress(&$resArray) {
		$address = array();
		$address["shipping_firstname"] = $resArray["PAYMENTREQUEST_0_SHIPTONAME"];
		$address["shipping_lastname"] = '';
		$address["shipping_address_1"] = $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"];
		if (isset($resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"])) {
			$address["shipping_address_2"] = $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"];
		}
		else {
			$address["shipping_address_2"] = '';
		}
		$address["shipping_city"] = $resArray["PAYMENTREQUEST_0_SHIPTOCITY"];
		$address["shipping_zone"] = $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"];
		$address["shipping_zone_id"] = 0;
		$country = $this->db->queryOne("SELECT * FROM @@country WHERE iso_code_2 = '" . $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"] . "'");
		if ($country) {
			$address["shipping_country_id"] = $country["country_id"];
			$address["shipping_country"] = $country["name"];
			$zone = $this->db->queryOne("SELECT zone_id, name FROM @@zone WHERE code = '" . $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"] . "' AND country_id = '" . $country["country_id"] . "'");
			if ($zone) {
				$address["shipping_zone"] = $zone["name"];
				$address["shipping_zone_id"] = $zone["zone_id"];
			}
		}
		else {
			$address["shipping_country_id"] = 0;
			$address["shipping_country"] = $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"];
		}
		
		$address["shipping_postcode"] = $resArray["PAYMENTREQUEST_0_SHIPTOZIP"];
		$address["email"] = $resArray["EMAIL"];
		$address["telephone"] = isset($resArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"]) ? $resArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"] : (isset($resArray["PHONENUM"]) ? $resArray["PHONENUM"] : '');
		$address["comment"] = isset($resArray["PAYMENTREQUEST_0_NOTETEXT"]) ? $resArray["PAYMENTREQUEST_0_NOTETEXT"] : '';
		return $address;
	}
	
	//use for GetExpressCheckoutDetails
	public function getPayerInfo(&$resArray) {
		return array(
			'email' => $resArray["EMAIL"],
			'payer_id' => $resArray["PAYERID"],
			'payer_status' => $resArray["PAYERSTATUS"],//verified or unverified
			'country_code' => $resArray["COUNTRYCODE"],
			'firstname' => $resArray["FIRSTNAME"],
			'lastname' => $resArray["LASTNAME"]
		);
	}
	
	public function ConfirmPayment($invNum, $paymentAmount, $token = null, $address = array()) {
		if (!$token && isset($this->session->data['PE_TOKEN'])) {
			$token = $this->session->data['PE_TOKEN'];
		}
		$nvpArray = array();
		$nvpArray["TOKEN"] = $token;
		$nvpArray["PAYMENTREQUEST_0_INVNUM"] = $invNum;
		$nvpArray["PAYERID"] = $this->session->data['PE_PAYER_ID'];
		$nvpArray["PAYMENTREQUEST_0_PAYMENTACTION"] = $this->paymentType;
		$nvpArray["PAYMENTREQUEST_0_AMT"] = $paymentAmount;
		$nvpArray["PAYMENTREQUEST_0_CURRENCYCODE"] = $this->currency->getCode();
		if (!empty($address)) {
			$countryCode = $this->db->queryOne("SELECT iso_code_2 FROM @@country WHERE country_id = '" . $address["shipping_country_id"] . "'");
			$zoneCode = $this->db->queryOne("SELECT code FROM `@@zone` WHERE zone_id = '" . $address["shipping_zone_id"] . "'");
			$nvpArray["PAYMENTREQUEST_0_SHIPTONAME"] = $address["shipping_firstname"] . ' ' . $address["shipping_lastname"];;
			$nvpArray["PAYMENTREQUEST_0_SHIPTOSTREET"] = $address["shipping_address_1"];
			if (!empty($address["shipping_address_2"])){
				$nvpArray["PAYMENTREQUEST_0_SHIPTOSTREET2"] = $address["shipping_address_2"];
			}
			$nvpArray["PAYMENTREQUEST_0_SHIPTOCITY"] = $address["shipping_city"];
			$nvpArray["PAYMENTREQUEST_0_SHIPTOSTATE"] = $zoneCode;
			$nvpArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"] = $countryCode;
			$nvpArray["PAYMENTREQUEST_0_SHIPTOZIP"] = $address["shipping_postcode"];
			$nvpArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"] = $address["telephone"];
		}
		$resArray = $this->hash_call("DoExpressCheckoutPayment", $nvpArray);
		$ack = strtoupper($resArray["ACK"]);
		if($ack != "SUCCESS" && $ack != "SUCCESSWITHWARNING") {
			$this->ERROR = TRUE;
			$this->logErrorMsg($resArray, "DoExpressCheckoutPayment");
		}
		else {
			$paymentInfo = $this->getPaymentInfo($resArray);
			$order_id = $this->session->data['order_id'];
			$paymentInfo['order_id'] = $order_id;
			
			$id = $this->db->queryOne("SELECT id FROM @@paypal_express_payment WHERE order_id = '$order_id'");
			if ($id) {
				$this->db->update("paypal_express_payment", $paymentInfo, "id=$id");
			}
			else {
				$paymentInfo['date_added'] = date('Y-m-d H:i:s');
				$this->db->insert("paypal_express_payment", $paymentInfo);
			}
			$data = array (
				"payment_transaction_id" => $paymentInfo["transaction_id"],
				"payment_type" => $paymentInfo["payment_type"],
				"payment_fee_amt" => $paymentInfo["fee_amt"],
				"payment_status" => $paymentInfo["payment_status"],
			);
			$this->db->update('order', $data, array('order_id' => $paymentInfo["order_id"]));
		}
		return $resArray;
	}
	//use for DoExpressCheckoutPayment
	public function getPaymentInfo(&$resArray) {
		return array(
			"transaction_id" => $resArray["PAYMENTINFO_0_TRANSACTIONID"],
			"payment_type" => $resArray["PAYMENTINFO_0_PAYMENTTYPE"],//none, echeck, instance
			"order_time" => $resArray["PAYMENTINFO_0_ORDERTIME"],
			"amt" => $resArray["PAYMENTINFO_0_AMT"],
			"currency_code" => $resArray["PAYMENTINFO_0_CURRENCYCODE"],
			"fee_amt" => isset($resArray["PAYMENTINFO_0_FEEAMT"]) ? $resArray["PAYMENTINFO_0_FEEAMT"] : 0,//PayPal fee amount charged for the transaction
			//"settle_amt" => $resArray["PAYMENTINFO_0_SETTLEAMT"],//Amount deposited in your PayPal account after a currency conversion
			"payment_status" => $resArray["PAYMENTINFO_0_PAYMENTSTATUS"],			
			"pending_reason" => $resArray["PAYMENTINFO_0_PENDINGREASON"],
			"reason_code" => $resArray["PAYMENTINFO_0_REASONCODE"]
		);
	}
	
	/*'--------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	 Inputs:  $token.
	 Returns: 
	----------------------------------------------------------------------------------
	*/
	public function redirectToPayPal($token = null) {
		if (!$token && isset($this->session->data['PE_TOKEN'])) {
			$token = $this->session->data['PE_TOKEN'];
		}
		$payPalURL = $this->PAYPAL_URL . $token;
		header("Location: ".$payPalURL);
	}
	
	public function getPaypalURL($token = null) {
		if (!$token && isset($this->session->data['PE_TOKEN'])) {
			$token = $this->session->data['PE_TOKEN'];
		}
		return $this->PAYPAL_URL . $token;
	}
	
	/**----------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  *-------------------------------------------------------------------------------
	*/
	function hash_call($methodName, $nvpArray) {
		//declaring of global variables
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($this->USE_PROXY) {
			curl_setopt ($ch, CURLOPT_PROXY, $this->PROXY_HOST. ":" . $this->PROXY_PORT); 
		}
		
		//NVPRequest for submitting to server
		$nvpReqArray = $nvpArray;
		$nvpReqArray["METHOD"] = $methodName;
		$nvpReqArray["VERSION"] = $this->version;
		$nvpReqArray["PWD"] = $this->API_Password;
		$nvpReqArray["USER"] = $this->API_UserName;
		$nvpReqArray["SIGNATURE"] = $this->API_Signature;
		$nvpReqStr = $this->formatNVP($nvpReqArray);
        //setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpReqStr);

		//getting response from server
		$response = curl_exec($ch);
		
		//log paypal express datas
		$order_id = 0;
		if (isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		}		
		
		if (curl_errno($ch)) {
			$curl_error_no = curl_errno($ch) ;
			$curl_error_msg = curl_error($ch);
			$this->log->write('CURL ERROR - ' . $curl_error_no . ', ' . $curl_error_msg);
		}
		else {
		  	curl_close($ch);
		}

		$resArray = $this->deformatNVP($response);
		
		$logData = array(
			'order_id' => $order_id,
			'token' => isset($nvpArray["TOKEN"]) ? $nvpArray["TOKEN"] : '',
			'method' => $methodName,
			'request' => $this->getLogMsg($nvpArray),
			'response' => $this->getLogMsg($resArray),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'date_added' => date('Y-m-d H:i:s')
		);
		$this->db->insert('paypal_express', $logData);
		
		return $resArray;
	}	
	
	function getLogMsg($array) {
		$string = array();
		foreach ($array as $name => $value) {
			// remove quotation marks
			$value = str_replace('"', '', $value);
			$string[] = $name . '=' . $value;
		}
		return implode('&', $string);
	}
	
	function formatNVP($nvpArray) {
		$string = array();
		foreach ($nvpArray as $name => $value) {
			// remove quotation marks
			$value = str_replace('"', '', $value);
			$string[] = $name . '=' . urlencode($value);
		}
		return implode('&', $string);
	}
	
	/*'---------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpStr is NVPString.
	  * @nvpArray is Associative Array.
	 ----------------------------------------------------------------------------------
	  */
	function deformatNVP($nvpStr) {
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpStr)) {
			//postion of Key
			$keypos = strpos($nvpStr, '=');
			//position of value
			$valuepos = strpos($nvpStr,'&') ? strpos($nvpStr,'&'): strlen($nvpStr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval = substr($nvpStr, $intial, $keypos);
			$valval = substr($nvpStr, $keypos+1, $valuepos-$keypos-1);
			
			//decoding the respose
			$nvpArray[urldecode($keyval)] = urldecode($valval);
			$nvpStr = substr($nvpStr, $valuepos+1, strlen($nvpStr));
	    }
		
		return $nvpArray;
	}
}
?>
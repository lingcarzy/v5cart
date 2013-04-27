<?php echo $header; ?>
<div id="content">
   <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_delete']; ?></span></a>
	  <a href="<?php echo UA('catalog/product_group');?>" class="button"><span><?php echo $_['button_cancel']; ?></span></a>
	  </div>
    </div>
    <div class="content">
	  <table width="100%">
	  <tr valign="top">
	  <td width="400">
		<form action="<?php echo UA('catalog/product_group/product_add', 'product_group_id=' . $product_group_id); ?>" method="post" enctype="multipart/form-data" id="formadd">
		<table class="form">
        <tr>
          <td>Products:</td>
          <td><input type="text" name="product" value="" size="50"/></td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td><div class="scrollbox" id="products"></div>
		  <input type="hidden" name="product_ids" value="" /></td>
        </tr>
		 <tr>
          <td>&nbsp;</td>
          <td><a onclick="$('#formadd').submit()" class="button"><span><?php echo $_['button_insert']; ?></span></a></td>
        </tr>
      </table>
	  </form>
	  </td>
	  <td>
	  <small><?php
		if ($products) {
			echo "Total: ".count($products);
		}
	  ?></small>
	  
      <form action="<?php echo UA('catalog/product_group/product_delete', 'product_group_id=' . $product_group_id); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td width="60">ID</td>
			  <td width="120">Image</td>
              <td  width="120">Model</td>
			  <td>Product</td>
			  <td  width="80">Price</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" /></td>
			  <td><?php echo $product['product_id']; ?></td>
              <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
			  <td><?php echo $product['model']; ?></td>
			  <td><a href="<?php echo HTTP_CATALOG;?><?php echo $product['link'];?>" target="_blank"><?php echo $product['name']; ?></a></td>
			  <td><?php echo $product['price']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="15"></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  </td></tr>
	  </table>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	minLength: 3,
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/product/autocomplete'); ?>',
			type: 'GET',
			dataType: 'json',
			data: 'filter_name=' +  encodeURIComponent(request.term),
			success: function(data) {
				response($.map(data, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#products' + ui.item.value).remove();
		
		$('#products').append('<div id="products' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');

		$('#products div:odd').attr('class', 'odd');
		$('#products div:even').attr('class', 'even');
		
		data = $.map($('#products input'), function(element){
			return $(element).attr('value');
		});
						
		$('input[name=\'product_ids\']').attr('value', data.join());
					
		return false;
	}
});

$('#products div img').live('click', function() {
	$(this).parent().remove();
	
	$('#products div:odd').attr('class', 'odd');
	$('#products div:even').attr('class', 'even');

	data = $.map($('#products input'), function(element){
		return $(element).attr('value');
	});
					
	$('input[name=\'product_ids\']').attr('value', data.join());	
});
//--></script> 
<?php echo $footer; ?>
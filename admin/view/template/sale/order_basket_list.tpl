<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /><?php echo $_['heading_title']?></h1>
      <div class="buttons">
	  Basket ID: <input type="text" name="basket_id" value="<?=$basket_id?>">
	  &nbsp;&nbsp; Order ID: <input type="text" name="order_id" value="<?=$order_id?>" size="8">
	  <a onclick="filter();" class="button"><span><?php echo $_['button_filter']; ?></span></a>&nbsp; | &nbsp;
	  <a onclick="location = '<?php echo UA('sale/order_basket/insert'); ?>'" class="button"><span><?php echo $_['button_insert']; ?></span></a>
	  <a onclick="$('form').submit();" class="button"><span><?php echo $_['button_delete']; ?></span></a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo UA('sale/order_basket/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left">ID</td>
			  <td>Date Created</td>
			  <td>Orders</td>
			  <td>SKUs</td>
			  <td>Status</td>
              <td>Remark</td>
			  <td class="right" width="100">Action</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($baskets) { ?>
            <?php foreach ($baskets as $basket) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $basket['basket_id']; ?>" />
              </td>
			   <td class="left"><?php echo $basket['basket_id']; ?></td>
              <td class="left"><?php echo date('d/m/Y H:i', $basket['date_created']); ?></td>
			  <td class="left"><?php echo $basket['total_orders']; ?></td>
			  <td class="left"><?php echo $basket['total_skus']; ?></td>
			  <td class="left"><?php echo $basket['status']; ?></td>
			  <td class="left"><?php echo $basket['remark']; ?></td>
			  <td class="right">
			  [ <a href="<?php echo UA('sale/order_basket/edit');?>&basket_id=<?php echo $basket['basket_id'];?>">Edit</a> ]
			  [ <a href="<?php echo UA('sale/order_basket/orders');?>&basket_id=<?php echo $basket['basket_id'];?>">Orders</a> ]
			  </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo UA('sale/order_basket'); ?>';
	
	var v = $('input[name=\'basket_id\']').attr('value');
	
	if (v) {
		url += '&basket_id=' + encodeURIComponent(v);
	}
	
	v = $('input[name=\'order_id\']').attr('value');
	
	if (v) {
		url += '&order_id=' + encodeURIComponent(v);
	}
	
	location = url;
}
//--></script> 
<?php echo $footer; ?>
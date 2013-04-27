<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <a href="<?php echo UA('common/home') ?>">Home</a>
	::
	<a href="<?php echo UA('sale/ppexpress/error') ?>">Paypal Express Errors</a>
  </div>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" />Paypal Express Errors</h1>
      <div class="buttons">
	  &nbsp;&nbsp; Order ID: <input type="text" name="order_id" value="<?php echo $order_id?>" size="8">
	  <a onclick="filter()" class="button"><span>Filter</span></a>
	  </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left">Order ID</td>
			  <td>Method</td>
			  <td>Error Code</td>
			  <td>MSG</td>
			  <td>Date Added</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($errors) { ?>
            <?php foreach ($errors as $e) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $e['error_id']; ?>" />
              </td>
              <td class="left"><?php echo $e['order_id']; ?></td>
			  <td><?php echo $e['method']; ?></td>
			  <td><?php echo $e['severity_code']; ?> (<?php echo $e['error_code']; ?>)</td>
			  <td><a class="tipTrigger" href="javascript:;" tip="<?php echo $e['long_msg']; ?>"><?php echo $e['short_msg']; ?></a></td>
			  <td><?php echo $e['date_added']; ?></td>
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
<script type="text/javascript">
function filter() {
	url = '<?php echo UA('sale/paypal_express/error') ?>';
	v = $('input[name=\'order_id\']').attr('value');
	if (v) {
		url += '&order_id=' + encodeURIComponent(v);
	}
	location = url;
}

$(".tipTrigger").powerFloat({showDelay: 200, hoverHold: false, targetMode: "tip", targetAttr: "tip"});
</script> 
<?php echo $footer; ?>
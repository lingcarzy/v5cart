<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <a href="<?php echo UA('common/home') ?>">Home</a>
	::
	<a href="<?php echo UA('sale/ppexpress/payment') ?>">Paypal Express Payments</a>
  </div>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" />Paypal Express Payments</h1>
      <div class="buttons">
	  Order ID: <input type="text" name="order_id" value="<?php echo $order_id?>" size="8">
	  &nbsp;&nbsp;Transaction ID: <input type="text" name="transaction_id" value="<?php echo $transaction_id?>" size="35">
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
			  <td>Transaction ID</td>
			  <td>Payer</td>
			  <td>Country</td>			 
			  <td>Type</td>
			  <td>Amt</td>
			  <td>Fee Amt</td>
              <td>Status</td>
			  <td>Date Added</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($payments) { ?>
            <?php foreach ($payments as $p) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $p['id']; ?>" />
              </td>
              <td><?php echo $p['order_id']; ?></td>
			  <td><a class="tipTrigger" href="javascript:;" rel="<?php echo UA('sale/paypal_express/payment_info'); ?>&id=<?php echo $p['id']; ?>"><?php echo $p['transaction_id']; ?></a></td>
			  <td><?php echo $p['email']; ?><br><?php echo $p['payer_status']; ?></td>
			  <td><?php echo $p['country_code']; ?></td>			 
			  <td><?php echo $p['payment_type']; ?></td>
			  <td><?php echo $p['amt']; ?></td>		  
			  <td><?php echo $p['fee_amt']; ?></td>
			  <td><?php echo $p['payment_status']; ?></td>
			  <td><?php echo $p['date_added']; ?></td>
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
	url = '<?php echo UA('sale/paypal_express/payment') ?>';
	v = $('input[name=\'order_id\']').attr('value');
	if (v) {
		url += '&order_id=' + encodeURIComponent(v);
	}
	v = $('input[name=\'transaction_id\']').attr('value');
	if (v) {
		url += '&transaction_id=' + encodeURIComponent(v);
	}
	location = url;
}
$(".tipTrigger").powerFloat({
	eventType: "click",
	targetMode: "ajax"
});
</script> 
<?php echo $footer; ?>
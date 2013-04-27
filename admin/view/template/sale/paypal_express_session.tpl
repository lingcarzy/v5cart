<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <a href="<?php echo UA('common/home') ?>">Home</a>
	::
	<a href="<?php echo UA('sale/ppexpress') ?>">Paypal Express Sessions</a>
  </div>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" />Paypal Express Sessions</h1>
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
			  <td>Token</td>
			  <td>Method</td>
              <td>IP</td>
			  <td>Date Added</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($sessions) { ?>
            <?php foreach ($sessions as $s) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $s['pe_id']; ?>" />
              </td>
              <td class="left"><?php echo $s['order_id']; ?></td>
			  <td><?php echo $s['token']; ?></td>
			  <td><a class="tipTrigger" href="javascript:;" rel="<?php echo UA('sale/paypal_express/session_info'); ?>&id=<?php echo $s['pe_id']; ?>"><?php echo $s['method']; ?></a></td>
			  <td><?php echo $s['ip']; ?></td>
			  <td><?php echo $s['date_added']; ?></td>
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
	url = '<?php echo UA('sale/paypal_express') ?>';
	v = $('input[name=\'order_id\']').attr('value');
	if (v) {
		url += '&order_id=' + encodeURIComponent(v);
	}
	location = url;
}
$(".tipTrigger").powerFloat({
	eventType: "click",
	targetMode: "ajax"
});
</script> 
<?php echo $footer; ?>
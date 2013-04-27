<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('sale/voucher'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a>
        <?php if ($voucher_id) { ?>
        <a href="#tab-history"><?php echo $_['tab_voucher_history']; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>" />
                <?php if (isset($error_code)) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_from_name']; ?></td>
              <td><input type="text" name="from_name" value="<?php echo $from_name; ?>" />
                <?php if (isset($error_from_name)) { ?>
                <span class="error"><?php echo $error_from_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_from_email']; ?></td>
              <td><input type="text" name="from_email" value="<?php echo $from_email; ?>" />
                <?php if (isset($error_from_email)) { ?>
                <span class="error"><?php echo $error_from_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_to_name']; ?></td>
              <td><input type="text" name="to_name" value="<?php echo $to_name; ?>" />
                <?php if (isset($error_to_name)) { ?>
                <span class="error"><?php echo $error_to_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_to_email']; ?></td>
              <td><input type="text" name="to_email" value="<?php echo $to_email; ?>" />
                <?php if (isset($error_to_email)) { ?>
                <span class="error"><?php echo $error_to_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_theme']; ?></td>
              <td><select name="voucher_theme_id">
				<?php echo form_select_option($voucher_themes, $voucher_theme_id, null, 'voucher_theme_id', 'name'); ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_message']; ?></td>
              <td><textarea name="message" cols="40" rows="5"><?php echo $message; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_amount']; ?></td>
              <td><input type="text" name="amount" value="<?php echo $amount; ?>" />
                <?php if (isset($error_amount)) { ?>
                <span class="error"><?php echo $error_amount; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php if ($voucher_id) { ?>
        <div id="tab-history">
          <div id="history"></div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<?php if ($voucher_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('<?php echo UA('sale/voucher/history'); ?>&voucher_id=<?php echo $voucher_id; ?>');
//--></script>
<?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>
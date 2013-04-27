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
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_title']; ?></td>
            <td><input type="text" name="title" value="<?php echo $title; ?>" />
              <?php if (isset($error_title)) { ?>
              <span class="error"><?php echo $error_title; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
            <td><input type="text" name="code" value="<?php echo $code; ?>" />
              <?php if (isset($error_code)) { ?>
              <span class="error"><?php echo $error_code; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_symbol_left']; ?></td>
            <td><input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_symbol_right']; ?></td>
            <td><input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_decimal_place']; ?></td>
            <td><input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_value']; ?></td>
            <td><input type="text" name="value" value="<?php echo $value; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true); ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
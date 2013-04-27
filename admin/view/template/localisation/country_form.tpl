<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">    <div class="heading">
      <h1><img src="view/image/country.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/country'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if (isset($error_name)) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_iso_code_2']; ?></td>
            <td><input type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_iso_code_3']; ?></td>
            <td><input type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_address_format']; ?></td>
            <td><textarea name="address_format" cols="40" rows="5"><?php echo $address_format; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_postcode_required']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'postcode_required', $postcode_required); ?></td>
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
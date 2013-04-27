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
      <h1><img src="view/image/country.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/zone'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
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
            <td><?php echo $_['entry_code']; ?></td>
            <td><input type="text" name="code" value="<?php echo $code; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_country']; ?></td>
            <td><select name="country_id">
                <?php echo form_select_option($countries, $country_id, true);?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                <option value="0"><?php echo $_['text_disabled']; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $_['text_enabled']; ?></option>
                <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
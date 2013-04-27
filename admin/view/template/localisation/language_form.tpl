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
      <h1><img src="view/image/language.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
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
            <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
            <td><input type="text" name="code" value="<?php echo $code; ?>" />
              <?php if (isset($error_code)) { ?>
              <span class="error"><?php echo $error_code; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_locale']; ?></td>
            <td><input type="text" name="locale" value="<?php echo $locale; ?>" />
              <?php if (isset($error_locale)) { ?>
              <span class="error"><?php echo $error_locale; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_image']; ?></td>
            <td><input type="text" name="image" value="<?php echo $image; ?>" />
              <?php if (isset($error_image)) { ?>
              <span class="error"><?php echo $error_image; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_directory']; ?></td>
            <td><input type="text" name="directory" value="<?php echo $directory; ?>" />
              <?php if (isset($error_directory)) { ?>
              <span class="error"><?php echo $error_directory; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_filename']; ?></td>
            <td><input type="text" name="filename" value="<?php echo $filename; ?>" />
              <?php if (isset($error_filename)) { ?>
              <span class="error"><?php echo $error_filename; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
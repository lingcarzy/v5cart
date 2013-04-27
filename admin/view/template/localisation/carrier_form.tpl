<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/carrier'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['text_code']; ?></td>
            <td><input type="text" name="code" value="<?php echo $code; ?>" />
				<?php echo form_error('code');?>
				</td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['text_name']; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
			<?php echo form_error('name');?>
				</td>
          </tr>
          <tr>
            <td><?php echo $_['text_tracking_link']; ?></td>
            <td><input type="text" name="tracking_link" value="<?php echo $tracking_link; ?>" size="93"/></td>
          </tr>
          <tr>
            <td><?php echo $_['text_description']; ?></td>
            <td><textarea name="description" cols="90" rows="3"><?php echo $description; ?></textarea></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/weight_class'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_title']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['title'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php 
			  $e = "error_title_" . $language['language_id'];
				if (isset($$e)) { 
				?>
				  <span class="error"><?php echo $$e; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_unit']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['unit'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
               <?php 
			  $e = "error_unit_" . $language['language_id'];
				if (isset($$e)) { 
				?>
				   <span class="error"><?php echo $$e; ?></span><br />
			   <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_value']; ?></td>
            <td><input type="text" name="value" value="<?php echo $value; ?>" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
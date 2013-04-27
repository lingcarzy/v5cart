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
      <h1><img src="view/image/information.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/attribute'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		  <tr>
            <td><?php echo $_['entry_type']; ?></td>
            <td><select name="type">
				<?php echo form_select_option($_['attribute_types'], $type, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
               <?php  echo form_error("attribute_description[{$language['language_id']}][name]");?>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*</span> <?php echo $_['entry_value']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <textarea name="attribute_description[<?php echo $language['language_id']; ?>][value]" cols="40" rows="3"><?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['value'] : ''; ?></textarea>
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php } ?></td>
          </tr>
		  <tr>
            <td><?php echo $_['entry_extend']; ?></td>
            <td><textarea name="extend" cols="40" rows="5"><?php echo $extend; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_attribute_group']; ?></td>
            <td><select name="attribute_group_id">
				<?php echo form_select_option($attribute_groups, $attribute_group_id, null, 'attribute_group_id', 'name'); ?>
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
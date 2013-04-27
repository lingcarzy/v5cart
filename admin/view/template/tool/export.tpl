<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
    </div>
    <div class="content">
      <form action="<?php echo UA('tool/export'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td colspan="2"><?php echo $_['entry_description']; ?></td>
          </tr>
          <tr>
            <td width="35%"><?php echo $_['entry_restore']; ?></td>
            <td><input type="file" name="upload" />&nbsp;&nbsp;<a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_import']; ?></span></a></td>
          </tr>
        </table>
      </form>
      <form action="<?php echo UA('tool/export/download'); ?>" method="post" id="formExport">
        <table class="form">
          <tr>
            <td width="35%" valign="top"><?php echo $_['entry_export']; ?></td>
            <td>
			<p><input type="checkbox" onclick="$('input[name*=\'product_category\']').attr('checked', this.checked);" checked /><b>Categories:</b></p>
			<div>
			<ul id="category_tree">
				  <?php $level = 0; ?>
                  <?php foreach ($categories as $category) { ?>
					<?php if ($category['_level'] > $level) { ?>
					<ul>
					<?php } elseif ($category['_level'] < $level) { ?>
					</ul>
					<?php } ?>
					<li><input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" /><label><?php echo $category['name']; ?></label>					
					<?php if ($category['_leaf']) { ?>
					</li>
					<?php } ?>
					<?php $level = $category['_level']; ?>
				  <?php }?>
				</ul>
			</div>
			<p><input type="checkbox" onclick="$('input[name*=\'option_\']').attr('checked', this.checked);" /><b>Tables:</b></p>
			<div class="scrollbox">
				<table>
				<tr>
				<td width="100"><input type="checkbox" name="option_categories" value="1"> Categories</td>
				<td><input type="checkbox" name="option_products" value="1" checked> Products</td>
				</tr>
				<tr>
				<td><input type="checkbox" name="option_options" value="1"> Options</td>
				<td><input type="checkbox" name="option_attributes" value="1"> Attributes</td>
				</tr>
				<tr>
				<td><input type="checkbox" name="option_specials" value="1"> Specials</td>
				<td><input type="checkbox" name="option_discounts" value="1"> Discounts</td>
				</tr>
				<tr>
				<td><input type="checkbox" name="option_rewards" value="1"> Rewards</td>
				<td></td>
				</tr>
				</table>				
			</div>
			<p><a onclick="$('#formExport').submit();" class="button"><span><?php echo $_['button_export']; ?></span></a></p></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script>
$('#category_tree').checkboxTree({ onCheck: { ancestors: null, descendants: null}, onUncheck: {descendants: null},initializeUnchecked:'collapsed', initializeChecked: 'collapsed'});
</script>
<?php echo $footer; ?>
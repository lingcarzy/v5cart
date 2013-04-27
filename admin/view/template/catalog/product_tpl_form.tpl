<?php echo $header; ?>
<div id="content">
   <div class="breadcrumb">
   <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /><?php echo $_['heading_title'];?></h1>
	   <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	   <a href="<?php echo UA('catalog/product_tpl'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action;?>" method="post" id="form">		
        <table class="form">
            <tr>
			  <td><span class="required">*</span> Title</td>
			  <td><input type="text" name="title" value="<?php echo $title;?>" size="80" data-rule-required="true" />
			  </td>
            </tr>
			<tr>
			  <td><span class="required">*</span> Template</td>
			  <td><textarea name="content" id="content1"><?php echo $content; ?></textarea></td>
            </tr>
			<tr>
				<td>Status</td>
				<td>
					<select name="status">
					<?php echo form_select_option($_['option_statuses'], $status, true);?>
					</select>
				</td>
            </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('content1', {
	filebrowserBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashBrowseUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserImageUploadUrl: '<?php echo UA('common/filemanager'); ?>',
	filebrowserFlashUploadUrl: '<?php echo UA('common/filemanager'); ?>'
});
//--></script> 
<?php echo $footer; ?>
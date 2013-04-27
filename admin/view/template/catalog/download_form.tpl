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
      <h1><img src="view/image/download.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('catalog/download'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="download_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php echo form_error('download_description['.$language['language_id'].'][name]'); ?>
			  <br/>
			  <?php } ?>
			  </td>
          </tr>
          <tr>
            <td><?php echo $_['entry_filename']; ?></td>
            <td><input type="text" name="filename" value="<?php echo $filename; ?>" /> <a id="button-upload" class="button"><?php echo $_['button_upload']; ?></a>
              <?php echo form_error('filename'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_mask']; ?></td>
            <td><input type="text" name="mask" value="<?php echo $mask; ?>" />
              <?php echo form_error('mask'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_remaining']; ?></td>
            <td><input type="text" name="remaining" value="<?php echo $remaining; ?>" size="6" /></td>
          </tr>
          <?php if ($download_id) { ?>
          <tr>
            <td><?php echo $_['entry_update']; ?></td>
            <td><?php if ($update) { ?>
              <input type="checkbox" name="update" value="1" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="update" value="1" />
              <?php } ?></td>
          </tr>
          <?php } ?>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ajaxupload.js"></script> 
<script type="text/javascript"><!--
new AjaxUpload('#button-upload', {
	action: '<?php echo UA('catalog/download/upload'); ?>',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-upload').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-upload').attr('disabled', false);
		
		if (json['success']) {
			alert(json['success']);
			
			$('input[name=\'filename\']').attr('value', json['filename']);
			$('input[name=\'mask\']').attr('value', json['mask']);
		}
		
		if (json['error']) {
			alert(json['error']);
		}
		
		$('.loading').remove();	
	}
});
//--></script> 
<?php echo $footer; ?>
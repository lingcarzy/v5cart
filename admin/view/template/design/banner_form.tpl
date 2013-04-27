<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
   <?php echo bread_crumbs();?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('design/banner'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
              <?php echo form_error('name') ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="status">
			<?php echo form_select_option($_['option_statuses'], $status, true);?>
              </select></td>
          </tr>
        </table>
        <table id="images" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $_['entry_title']; ?></td>
              <td class="left"><?php echo $_['entry_link']; ?></td>
              <td class="left"><?php echo $_['entry_image']; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $image_row = 0; ?>
          <?php foreach ($banner_images as $banner_image) { ?>
          <tbody id="image-row<?php echo $image_row; ?>">
            <tr>
              <td class="left"><?php foreach ($languages as $language) { ?>
                <input type="text" name="banner_image[<?php echo $image_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($banner_image['title'][$language['language_id']]) ? $banner_image['title'][$language['language_id']] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php echo form_error("banner_image[$image_row][title][{$language['language_id']}]"); ?>
                <?php } ?></td>
              <td class="left"><input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" /></td>
              <td class="left"><div class="image"><img src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                  <input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="image<?php echo $image_row; ?>"  />
                  <br />
                  <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $_['text_clear']; ?></a></div></td>
              <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addImage();" class="button"><?php echo $_['button_add_banner']; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '<tr>';
    html += '<td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="banner_image[' + image_row + '][title][<?php echo $language['language_id']; ?>][title]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '</td>';	
	html += '<td class="left"><input type="text" name="banner_image[' + image_row + '][link]" value="" /></td>';	
	html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\', \'<?php echo $token;?>\');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $_['text_clear']; ?></a></div></td>';
	html += '<td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '</tr>';
	html += '</tbody>'; 
	
	$('#images tfoot').before(html);
	
	image_row++;
}
//--></script>
<?php echo $footer; ?>
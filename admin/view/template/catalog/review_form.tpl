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
      <h1><img src="view/image/review.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a onclick="location = '<?php echo UA('catalog/review'); ?>';" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_author']; ?></td>
            <td><input type="text" name="author" value="<?php echo $author; ?>" />
              <?php echo form_error('author') ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_product']; ?></td>
            <td><input type="text" name="product" value="<?php echo $product; ?>" />
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <?php echo form_error('product_id') ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_text']; ?></td>
            <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
              <?php echo form_error('text') ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_rating']; ?></td>
            <td><b class="rating"><?php echo $_['entry_bad']; ?></b>&nbsp;
              <?php if ($rating == 1) { ?>
              <input type="radio" name="rating" value="1" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="1" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 2) { ?>
              <input type="radio" name="rating" value="2" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="2" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 3) { ?>
              <input type="radio" name="rating" value="3" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="3" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 4) { ?>
              <input type="radio" name="rating" value="4" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="4" />
              <?php } ?>
              &nbsp;
              <?php if ($rating == 5) { ?>
              <input type="radio" name="rating" value="5" checked />
              <?php } else { ?>
              <input type="radio" name="rating" value="5" />
              <?php } ?>
              &nbsp; <b class="rating"><?php echo $_['entry_good']; ?></b>
              <?php echo form_error('rating') ?></td>
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
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('catalog/product/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'product\']').val(ui.item.label);
		$('input[name=\'product_id\']').val(ui.item.value);
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<?php echo $footer; ?>
<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a href="<?php echo UA('extension/total'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('total/handling'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['entry_total']; ?></td>
            <td><input type="text" name="handling_total" value="<?php echo $handling_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_fee']; ?></td>
            <td><input type="text" name="handling_fee" value="<?php echo $handling_fee; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax_class']; ?></td>
            <td><select name="handling_tax_class_id">
                  <option value="0"><?php echo $_['text_none']; ?></option>
				  <?php echo form_select_option($tax_classes, $handling_tax_class_id, null, 'tax_class_id', 'title'); ?>
                </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="handling_status">
			<?php echo form_select_option($_['option_statuses'], $handling_status, true); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="handling_sort_order" value="<?php echo $handling_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
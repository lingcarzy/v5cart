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
      <h1><img src="view/image/tax.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a href="<?php echo UA('localisation/tax_class/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('localisation/tax_class/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo $order; ?>"><?php echo $_['column_title']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $_['column_title']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($tax_classes) { ?>
            <?php foreach ($tax_classes as $tax_class) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($tax_class['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $tax_class['title']; ?></td>
              <td class="right"><?php foreach ($tax_class['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="3"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
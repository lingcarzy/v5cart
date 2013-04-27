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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a href="<?php echo UA('localisation/carrier/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('localisation/carrier/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left" width="100"><?php echo $_['text_code']; ?></td>
			  <td class="left" width="180"><?php echo $_['text_name']; ?></td>
			  <td class="left" width="300"><?php echo $_['text_tracking_link']; ?></td>
			  <td class="left"><?php echo $_['text_description']; ?></td>
              <td class="right" width="100"><?php echo $_['text_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($carriers) { ?>
            <?php foreach ($carriers as $carrier) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $carrier['carrier_id']; ?>" />
              </td>
			  <td class="left"><?php echo $carrier['code']; ?></td>
			  <td class="left"><?php echo $carrier['name']; ?></td>
			  <td class="left"><?php echo $carrier['tracking_link']; ?></td>
              <td class="left"><?php echo nl2br($carrier['description']); ?></td>
              <td class="right">
				<a href="<?php echo UA('localisation/carrier/update', 'carrier_id='. $carrier['carrier_id']);?>"><?php echo $_['text_edit']; ?></a>
			  </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
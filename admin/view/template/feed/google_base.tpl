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
      <h1><img src="view/image/feed.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('extension/feed'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('feed/google_base'); ?>" method="post" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['entry_status']; ?></td>
            <td><select name="google_base_status">
                <?php if ($google_base_status) { ?>
                <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                <option value="0"><?php echo $_['text_disabled']; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $_['text_enabled']; ?></option>
                <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_data_feed']; ?></td>
            <td><textarea cols="40" rows="5"><?php echo $data_feed; ?></textarea></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
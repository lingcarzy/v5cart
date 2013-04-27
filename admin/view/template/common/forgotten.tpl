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
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#forgotten').submit();" class="button"><?php echo $_['button_reset']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
        <p><?php echo $_['text_email']; ?></p>
        <table class="form">
          <tr>
            <td><?php echo $_['entry_email']; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
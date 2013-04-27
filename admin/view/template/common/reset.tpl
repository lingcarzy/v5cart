<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#reset').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="reset">
        <p><?php echo $_['text_password']; ?></p>
        <table class="form">
          <tr>
            <td><?php echo $_['entry_password']; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>" />
              <?php if (isset($error_password)) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_confirm']; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php if (isset($error_confirm)) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
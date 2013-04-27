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
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('user/user'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_username']; ?></td>
            <td><input type="text" name="username" value="<?php echo $username; ?>" />
              <?php echo form_error('username'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
              <?php echo form_error('firstname'); ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>
            <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
             <?php echo form_error('lastname'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_email']; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_user_group']; ?></td>
            <td><select name="user_group_id">
				<?php echo form_select_option($user_groups, $user_group_id, null, 'user_group_id', 'name');?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_password']; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"  />
              <?php echo form_error('password'); ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_confirm']; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php echo form_error('confirm'); ?></td>
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
<?php echo $footer; ?> 
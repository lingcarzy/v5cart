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
      <h1><img src="view/image/user-group.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('user/user_permission'); ?>" class="button"><?php echo $_['button_cancel']; ?></a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if (isset($error_name)) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_access']; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($permissions as $permission) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($permission, $access)) { ?>
                  <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                  <?php echo $permission; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                  <?php echo $permission; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_modify']; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($permissions as $permission) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($permission, $modify)) { ?>
                  <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
                  <?php echo $permission; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
                  <?php echo $permission; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $_['text_select_all']; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $_['text_unselect_all']; ?></a></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
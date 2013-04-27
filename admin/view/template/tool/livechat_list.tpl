<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo UA('tool/livechat/insert'); ?>'" class="button"><?php echo $_['button_insert']; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $_['button_delete']; ?></a><a onclick="location = '<?php echo UA('tool/livechat/setting'); ?>'" class="button"><?php echo $_['button_setting']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('tool/livechat/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="right" width="60"><?php echo $_['column_listorder']; ?></td>			  
              <td class="left" width="120"><?php echo $_['column_name']; ?></td>
              <td class="left" width="150"><?php echo $_['column_label']; ?></td>
			  <td class="left" width="130"><?php echo $_['column_type']; ?></td>
			  <td class="left"><?php echo $_['column_image']; ?></td>
			  <td class="left"><?php echo $_['column_code']; ?></td>
			  <td class="left" width="30"><?php echo $_['column_status']; ?></td>
              <td class="right" width="30"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($livechats) { ?>
            <?php foreach ($livechats as $livechat) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $livechat['chatid']; ?>" />
              </td>
              <td class="left"><input type="text" name="listorder[<?php echo $livechat['chatid']; ?>]" value="<?php echo $livechat['listorder'];?>" size="3"></td>
			  <td class="left"><?php echo $livechat['name'];?></td>
			  <td class="left"><?php echo $livechat['label'];?></td>
			  <td class="left"><?php echo $livechat['type'];?></td>
			  <td class="left">
				<?php if ($livechat['skin']) { ?>
					<img src="<?php echo $livechat['skin']; ?>" />
				<?php } ?>
			  </td>
			  <td class="left"><?php echo $livechat['code'];?></td>
			  <td class="left"><?php echo $livechat['status'] == 1 ? $_['text_enabled'] : $_['text_disabled'];?></td>
              <td class="right"><?php foreach ($livechat['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  <div class="buttons">
		<a onclick="$('#form').attr('action', '<?php echo UA('tool/livechat/index');?>');$('#form').submit();" class="button"><?php echo $_['button_update']; ?></a>
	  </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
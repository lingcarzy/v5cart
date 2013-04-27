<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a onclick="location = '<?php echo UA('tool/livechat'); ?>';" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('tool/livechat/setting'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
			<tr>
				<td><?php echo $_['text_title']; ?></td>
				<td><input type="text" name="title" value="<?php echo $setting['title']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $_['text_skin']; ?></td>
				<td>
				<select name="skin">
				<?php echo form_select_option($skins, $setting['skin']);?>
				</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $_['text_posx']; ?></td>
				<td><input type="text" name="posx" value="<?php echo $setting['posx']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $_['text_posy']; ?></td>
				<td><input type="text" name="posy" value="<?php echo $setting['posy']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $_['column_status']; ?></td>
				<td>
					<select name="enabled">
						<option value="1"<?php echo ($setting['enabled']==1)? ' selected' : ''; ?>><?php echo $_['text_enabled']; ?></option>
						<option value="0"<?php echo ($setting['enabled']==0)? ' selected' : ''; ?>><?php echo $_['text_disabled']; ?></option>
					</select>
				</td>
			</tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
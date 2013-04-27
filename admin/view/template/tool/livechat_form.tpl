<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('tool/livechat'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['column_label']; ?></td>
            <td><input type="text" name="label" value="<?php echo $label; ?>" /></td>
          </tr>
			<tr>
				<td><?php echo $_['column_type']; ?></td>
				<td>
					<select name="type" onchange="load_default_skin(this.value)" id="type">
						<?php foreach($livechat_type as $t => $l) { ?>
						<?php if ($t == $type) { ?>
						<option value="<?php echo $t;?>" selected><?php echo $l?></option>
						<?php } else { ?>
						<option value="<?php echo $t;?>"><?php echo $l?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</td>
			</tr>			
			<tbody id="row-skin"<?php if ($type == 'CODE'){ ?> style="display:none"<?php } ?>>
			<tr>
				<td><?php echo $_['column_name']; ?></td>
				<td><input type="text" name="name" value="<?php echo $name; ?>" />
				<input type="checkbox" name="ifhide" value="1"<?php echo ($ifhide==0) ? '' : ' checked'?>> <?php echo $_['text_display'];?>
					</td>
			</tr>
			<tr>
				<td><?php echo $_['column_image']; ?>
					<span class="help"><?php echo $_['text_image'];?></span>
				</td>
				<td>
					<select name="skin" onchange="preview()" id="skin">
						<option value=""></option>
						<?php
							if (isset($livechat_skin[$type])) {
								foreach ($livechat_skin[$type] as $k => $v) {
						?>
							<?php if ($skin == "$k") {?>
							<option value="<?php echo $k; ?>" selected><?php echo $v;?></option>
							<?php } else { ?>
							<option value="<?php echo $k; ?>"><?php echo $v;?></option>
							<?php } ?>
						<?php
								}
							}
						?>
					</select><br>
					<div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
						<input type="hidden" name="image" value="<?php echo $image; ?>" id="image"  />
						<br />
					<a onclick="image_upload('image', 'thumb', '<?php echo $token;?>');"><?php echo $_['text_browse']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $_['text_clear']; ?></a></div>
				</td>
			</tr>
			</tbody>
			<tr>
				<td><?php echo $_['column_code']; ?></td>
				<td><textarea name="code" cols="45" rows="5"><?php echo $code; ?></textarea></td>
			</tr>
          <tr>
            <td><?php echo $_['column_listorder']; ?></td>
            <td><input type="text" name="listorder" value="<?php echo $listorder; ?>" size="1" /></td>
          </tr>	
			<tr>
				<td><?php echo $_['column_status']; ?></td>
				<td>
					<select name="status">
						<option value="1"<?php echo ($status==1)? ' selected' : ''; ?>><?php echo $_['text_enabled']; ?></option>
						<option value="0"<?php echo ($status==0)? ' selected' : ''; ?>><?php echo $_['text_disabled']; ?></option>
					</select>
				</td>
			</tr>
        </table>
      </form>
	  <?php
		foreach($livechat_skin as $TYPE => $LIST) {
		?>
		  <select id="skin-<?php echo $TYPE; ?>" style="display: none;">
			  <option value=""></option>
				  <?php foreach ($LIST as $k => $v) { ?>
					  <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
				  <?php } ?>
		  </select>
		<?php
		}
	  ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function load_default_skin(type) {
	if (type == 'CODE') {
		$('#row-skin').css('display', 'none');
	}
	else {
		$('#row-skin').css('display', '');
	}
	html = $('#skin-' + type).html();
	$('#skin').html(html);
}

function preview(idx) {
	var sel = document.getElementById('skin');
	var image = sel.options[sel.selectedIndex].text;
	if (image == '') {
		$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');
	}
	else {
		image = "<?php echo HTTP_IMAGE?>/livechat/" + image;
		$('#thumb').attr('src', image); $('#image').attr('value', '');
	}
}
//--></script> 
<?php echo $footer; ?>
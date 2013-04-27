<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="view/AMF/template/Default/silverStyle.css" />
<script src="view/AMF/js/AmysqlFun.js"></script>
<script src="view/AMF/js/AmysqlConfig.js"></script>
<script src="view/AMF/js/AmysqlMain.js"></script>

<script src="view/AMF/js/PlugIn/AllTag/AmysqlAllTag.js"></script>
<script src="view/AMF/js/PlugIn/NewTag/AmysqlNewTag.js"></script>
<script src="view/AMF/js/PlugIn/AmysqlRecoveryTag.js"></script>
<script src="view/AMF/js/PlugIn/AmysqlNextTag.js"></script>
<script src="view/AMF/js/PlugIn/AmysqlPreviousTag.js"></script>
<script src="view/AMF/js/PlugIn/AmysqlCloseTag.js"></script>
</head>
<script>
window.onload = function() 
{
	G("AmysqlContent").src = "<?php echo UA('common/admincp/content');?>";
	_AmysqlTabJson = [{'type':'Activate','id':'home','name':'Home', 'url':'<?php echo UA('common/home'); ?>'}];
	_AmysqlLeftListJson = <?php echo $menu;?>;
	_AmysqlContent = window.frames.AmysqlContent;
	_AmysqlLeft = window.frames.AmysqlLeft;
	_AmysqlTag = window.frames.AmysqlTag;
}

</script>

<frameset rows="56,31,*" cols="*" border="0" framespacing="0">
	<frame src="<?php echo UA('common/admincp/header');?>">
	<frame  name="AmysqlTag" id="AmysqlTag" scrolling="No" frameborder="0" border="0" noresize >
	<frameset rows="*" cols="180,*" border="0"  framespacing="0">
		<frame  name="AmysqlLeft"  id="AmysqlLeft" frameborder="0" border="0" >
		<frame  scrolling="No" name="AmysqlContent" id="AmysqlContent"  frameborder="0" border="0">
	</frameset>
</frameset>
</HTML>

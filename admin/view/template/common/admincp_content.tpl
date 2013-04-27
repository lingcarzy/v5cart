<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="view/AMF/template/Default/silverStyle.css" />
<script src="view/AMF/js/AmysqlFun.js"></script>
</head>
<script src="view/AMF/js/AmysqlContent.js"></script>
<script>

var AmysqlContentObject = new AmysqlContent();
window.onload = function()
{
	parent._AmysqlTagLoad = true;
	
	parent.G("AmysqlTag").src = "<?php echo UA('common/admincp/tag');?>";
}
</script>
</head>
<frameset id="content" rows="100%,*" cols="*" frameborder="no" border="0" framespacing="0">
</frameset>
</html>
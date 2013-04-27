<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="view/AMF/template/Default/silverStyle.css" />
<script src="view/AMF/js/AmysqlFun.js"></script>
</head>
<script src="view/AMF/js/AmysqlTab.js"></script>
<script>
if(parent._AmysqlTagLoad)
{
	var AmysqlTabObject = new AmysqlTab();
	window.onload = function()
	{
		if (parent.AmysqlMainObject.AmysqlLoadComplete)
		{
			parent.AmysqlMainObject.AmysqlContentRun();
			parent.AmysqlMainObject.AmysqlTagRun();
		}

		parent._AmysqlLeftLoad = true;
		parent.G("AmysqlLeft").src = "<?php echo UA('common/admincp/left');?>";

		
	}
}
else
{
	parent.G("AmysqlContent").src = "<?php echo UA('common/home');?>";
}
</script>
<body id="navigation_body">
<div id="navigation" class="Normal">
<span id="Blank" class="Normal" style="padding:0px 4px;"></span>
</div>
</body>
</html>
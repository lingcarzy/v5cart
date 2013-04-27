<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="view/AMF/template/Default/silverStyle.css" />
<script src="view/AMF/js/AmysqlFun.js"></script>
</head>
<script src="view/AMF/js/AmysqlLeft.js"></script>
<script src="view/AMF/js/AmysqlLeftNavigation.js"></script>
<style>
body,html { overflow-x:hidden; }
</style>

<script>
var AmysqlLNO = new AmysqlLN();
var AmysqlLeftList = new AmysqlLeftListObject();
window.onload = function ()
{
	if (parent.AmysqlMainObject.AmysqlLoadComplete)
	{
		parent._AmysqlTagLoad = false;
		parent.AmysqlMainObject.AmysqlLeftRun();
		parent.AmysqlMainObject.AmysqlExtendInitialConfigRun();
	}
	else if(parent._AmysqlLeftLoad && parent._AmysqlTagLoad)
	{
		parent._AmysqlTagLoad = false;
		parent.AmysqlMainObject.AmysqlRun();
	}
	
}
</script>
</head>
<body id="left">
<div id="left_navigation"></div>
<div id="AmysqlLeftList"></div>
</body>
</html>
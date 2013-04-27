<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
</head>
<body>
<div id="container">
<div id="header">
<div class="div1">
    <div class="div2"><img src="view/image/logo.png"></div>
	<div class="links">
		<a><?php echo date('Y/m/d H:i');?></a>
		|
		<a href="<?php echo HTTP_CATALOG; ?>" target="_blank"><?php echo $_['text_front']; ?></a>
		|
		<a onclick="parent.location='<?php echo UA('common/logout');?>'"><?php echo $_['text_logout'];?></a>
	</div>
    <?php if ($logged) { ?>
    <div class="div3"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
    <?php } ?>
</div>
</div></div>
</body>
</html>

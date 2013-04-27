<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
 
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/setting.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons"></div>
    </div>
    <div class="content">
	<center>
		<h1><?php echo $_['text_wait']?></h1>
		<meta http-equiv="refresh" content="2;URL=<?php echo $forward;?>">
	</center>
    </div>
  </div>
</div>
<?php echo $footer; ?>
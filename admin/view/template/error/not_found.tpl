<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/error.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
	  <div class="buttons"><a onclick="history.back(-1);" class="button"><?php echo $_['button_back']; ?></a></div>
    </div>
    <div class="content">
      <div style="border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 15px;"><?php echo $_['text_not_found']; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
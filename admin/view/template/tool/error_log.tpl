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
      <h1><img src="view/image/log.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a href="<?php echo UA('tool/error_log/clear'); ?>" class="button"><?php echo $_['button_clear']; ?></a></div>
    </div>
    <div class="content">
      <textarea wrap="off" style="width: 98%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log; ?></textarea>
    </div>
  </div>
</div>
<?php echo $footer; ?>
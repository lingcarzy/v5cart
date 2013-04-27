<script src="catalog/view/javascript/jquery/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo C('config_template');?>/stylesheet/carousel.css" />

<div id="carousel<?php echo $module; ?>">
  <ul class="jcarousel-skin-v5cart">
    <?php foreach ($banners as $banner) { ?>
    <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
    <?php } ?>
  </ul>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?> ul').jcarousel({
	vertical: false,
	visible: <?php echo $limit; ?>,
	scroll: <?php echo $scroll; ?>
});
//--></script>
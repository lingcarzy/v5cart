<link rel="stylesheet" type="text/css" href="catalog/view/javascript/livechat/skin/<?php echo $setting['skin']; ?>/style.css" />
<script src="catalog/view/javascript/livechat/Floaters.js"></script>
<div class="livechat" id="livechat">
	<div class="lctop"><span><?php echo $setting['title']; ?></span></div>
	<div class="lcbox"><?php echo $livechat_code;?></div>
	<div class="lcbottom"></div>
</div>
<script>
var livechat = new Floaters();
livechat.addItem("livechat",<?php echo $setting['posx']; ?>,<?php echo $setting['posy']; ?>,"");
livechat.play("livechat");
</script>
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
      <h1><img src="view/image/setting.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons"></div>
    </div>
    <div class="content">
    <table width="900">
	<tr valign="top">
	<td width="400"><p><b><?php echo $_['text_sys_cache'];?></b>
	<ul>
	  <li>Category</li>
	  <li><b>Country</b></li>
	  <li><b>Zone</b></li>
	  <li>Currency</li>
	  <li>Language</li>
	  <li>Order status</li>
	  <li>Stock status</li>
	  <li>Length Class</li>
	  <li>Weight Class</li>
	  <li>Geo Zones and Tax rates</li>
	  <li><b>Extension (Module / Shipping / Payment / Total)</b></li>
	  <li><b>Store Settings</b></li>
	  <li>Layout route</li>
	  <li>Database Tables</li>
	</ul>
	<p><a href="<?php echo UA('setting/cache/update'); ?>" class="button"><?php echo $_['button_update']?></a></p>
	<p class="help">Bold items will be update automatically</p>
	</td>
	<td  width="250">
	<p>
		<b><?php echo $_['text_data_cache'];?></b><ul>
		<li>Query Data</li>
		<li>Module</li>		
		</ul>
	<a href="<?php echo UA('setting/cache/clear'); ?>" class="button"><?php echo $_['button_clear']; ?></a></p>
	</p></td>
	
	<td>
		<p>
			<b><?php echo $_['text_url_cache'];?></b><ul>
				<li>Product URL
				<span class="help">update when seo url rule changed</span>
				</li>
			</ul>
		<a href="<?php echo UA('setting/cache/url'); ?>" class="button"><?php echo $_['button_update']; ?></a></p>
	</p></td>
	
	</tr></table>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs();?>
  </div>
  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/setting.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('setting/seo_url/save'); ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
		<tr valign="top">
			<td>Category URL Rule:</td>
			<td>
			<input type="text" name="category_seo_rule" value="<?php echo $category_seo_rule; ?>" size="80">
			<p>
			Examples:
			<ul>
				<?php foreach($category_seo_rules as $rule) { ?>
				<li><?php echo $rule['rule'];?>
				<span class="help"><?php echo $rule['example'];?></span><br></li>
				<?php } ?>
			</ul>
			</p>
			</td>
		</tr>
		<tr valign="top">
			<td>Product URL Rule:</td>
			<td>
				<input type="text" name="product_seo_rule" value="<?php echo $product_seo_rule; ?>" size="80">
				<p>
					Examples:
					<ul>
						<?php foreach($product_seo_rules as $rule) { ?>
							<li><?php echo $rule['rule'];?>
							<span class="help"><?php echo $rule['example'];?></span><br></li>
						<?php } ?>
					</ul>
				</p>
			</td>
		</tr>
		</table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
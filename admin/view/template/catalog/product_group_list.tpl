<?php echo $header; ?>
<div id="content">
   <div class="breadcrumb">
   <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /><?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a href="<?php echo UA('catalog/product_group/edit'); ?>" class="button"><span><?php echo $_['button_insert']; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_delete'] ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/product_group/delete'); ?>" method="post" id="form">
        <table class="list">
          <thead>
            <tr height="30">
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td width="50">ID</td>
              <td width="100">Ref ID</td>
			  <td>Title</td>
			  <td width="120">Date Add</td>
			  <td width="120">Date Mod</td>
			  <td width="100">Status</td>
			  <td width="120">Action</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($product_groups) { ?>
            <?php foreach ($product_groups as $product_group) { ?>
            <tr  height="30" onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $product_group['product_group_id']; ?>" /></td>
			  <td><?php echo $product_group['product_group_id']; ?></td>
              <td><?php echo $product_group['ref_id']; ?></td>
			  <td><?php echo $product_group['title']; ?></td>
			  <td><?php echo date('Y/m/d H:i', $product_group['date_added']); ?></td>
			  <td><?php echo date('Y/m/d H:i', $product_group['date_modified']); ?></td>
              <td><?php echo $product_group['status']? $_['text_enabled'] : $_['text_disabled']; ?></td>
			  <td>				
				[<a href="<?php echo UA('catalog/product_group/edit'); ?>&product_group_id=<?php echo $product_group['product_group_id']?>">Edit</a>]
				
				[<a href="<?php echo UA('catalog/product_group/product'); ?>&product_group_id=<?php echo $product_group['product_group_id']?>">Products</a>]
			  </td>			  
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $_['text_no_results']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
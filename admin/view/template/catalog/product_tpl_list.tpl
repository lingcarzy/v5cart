<?php echo $header; ?>
<div id="content">
   <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons"><a onclick="location = '<?php echo UA('catalog/product_tpl/edit'); ?>'" class="button"><span><?php echo $_['button_insert']; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_delete']; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/product_tpl/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr height="30">
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td width="50">ID</td>
			  <td>Title</td>
			  <td width="120">Status</td>
			  <td width="100">Action</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($templates) { ?>
            <?php foreach ($templates as $template) { ?>
            <tr  height="30" onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $template['template_id']; ?>" /></td>
			  <td><?php echo $template['template_id']; ?></td>
			  <td><?php echo $template['title']; ?></td>
			  <td><?php echo $template['status'] ? $_['text_enabled'] : $_['text_disabled']; ?></td>
			  <td>				
				[<a href="<?php echo UA('catalog/product_tpl/edit'); ?>&template_id=<?php echo $template['template_id']?>"><?php echo $_['text_edit']; ?></a>]
			  </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="15"></td>
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
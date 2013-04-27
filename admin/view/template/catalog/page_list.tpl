<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a href="<?php echo UA('catalog/page/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a><a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a>
	  | <a href="<?php echo UA('catalog/page/html'); ?>" class="button"><?php echo $_['button_gen_html']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/page/delete'); ?>" method="post" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'id.title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo $order; ?>"><?php echo $_['column_title']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $_['column_title']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'i.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo $order; ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($pages) { ?>
            <?php foreach ($pages as $page) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($page['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $page['page_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $page['page_id']; ?>" />
                <?php } ?></td>
              <td class="left"><a href="../<?php echo $page['href'];?>" target="_blank"><?php echo $page['title']; ?></a></td>
              <td class="right"><?php echo $page['sort_order']; ?></td>
              <td class="right"><?php foreach ($page['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $_['text_no_results']; ?></td>
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
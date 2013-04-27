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
      <h1><img src="view/image/download.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a href="<?php echo UA('catalog/download/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a><a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/download/delete'); ?>" method="post" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'dd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'd.remaining') { ?>
                <a href="<?php echo $sort_remaining; ?>" class="<?php echo $order; ?>"><?php echo $_['column_remaining']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_remaining; ?>"><?php echo $_['column_remaining']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($downloads) { ?>
            <?php foreach ($downloads as $download) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($download['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $download['name']; ?></td>
              <td class="right"><?php echo $download['remaining']; ?></td>
              <td class="right"><?php foreach ($download['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $_['text_no_results']; ?></td>
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
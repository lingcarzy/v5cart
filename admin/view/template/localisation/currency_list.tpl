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
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a href="<?php echo $insert; ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'title') { ?>
                <a href="<?php echo $sort_title; ?>" class="<?php echo $order; ?>"><?php echo $_['column_title']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_title; ?>"><?php echo $_['column_title']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'code') { ?>
                <a href="<?php echo $sort_code; ?>" class="<?php echo $order; ?>"><?php echo $_['column_code']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_code; ?>"><?php echo $_['column_code']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'value') { ?>
                <a href="<?php echo $sort_value; ?>" class="<?php echo $order; ?>"><?php echo $_['column_value']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_value; ?>"><?php echo $_['column_value']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($currencies) { ?>
            <?php foreach ($currencies as $currency) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($currency['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $currency['title']; ?></td>
              <td class="left"><?php echo $currency['code']; ?></td>
              <td class="right"><?php echo $currency['value']; ?></td>
              <td class="left"><?php echo $currency['date_modified']; ?></td>
              <td class="right"><?php foreach ($currency['action'] as $action) { ?>
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
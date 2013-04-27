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
      <h1><img src="view/image/tax.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a href="<?php echo UA('localisation/tax_rate/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('localisation/tax_rate/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'tr.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'tr.rate') { ?>
                <a href="<?php echo $sort_rate; ?>" class="<?php echo $order; ?>"><?php echo $_['column_rate']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_rate; ?>"><?php echo $_['column_rate']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'tr.type') { ?>
                <a href="<?php echo $sort_type; ?>" class="<?php echo $order; ?>"><?php echo $_['column_type']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_type; ?>"><?php echo $_['column_type']; ?></a>
                <?php } ?></td> 
              <td class="left"><?php if ($sort == 'gz.name') { ?>
                <a href="<?php echo $sort_geo_zone; ?>" class="<?php echo $order; ?>"><?php echo $_['column_geo_zone']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_geo_zone; ?>"><?php echo $_['column_geo_zone']; ?></a>
                <?php } ?></td>                         
              <td class="left"><?php if ($sort == 'tr.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } ?></td>      
              <td class="left"><?php if ($sort == 'tr.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $_['column_date_modified']; ?></a>
                <?php } ?></td>                                                                                                                
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($tax_rates) { ?>
            <?php foreach ($tax_rates as $tax_rate) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;"><?php if ($tax_rate['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $tax_rate['name']; ?></td>
              <td class="right"><?php echo $tax_rate['rate']; ?></td>
              <td class="left"><?php echo $tax_rate['type']; ?></td>
              <td class="left"><?php echo $tax_rate['geo_zone']; ?></td>
              <td class="left"><?php echo $tax_rate['date_added']; ?></td>
              <td class="left"><?php echo $tax_rate['date_modified']; ?></td>
              <td class="right"><?php foreach ($tax_rate['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $_['text_no_results']; ?></td>
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
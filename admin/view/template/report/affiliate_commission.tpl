<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
    </div>
    <div class="content">
      <table class="form">
        <tr>
          <td><?php echo $_['entry_date_start']; ?>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $_['entry_date_end']; ?>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $_['column_affiliate']; ?></td>
            <td class="left"><?php echo $_['column_email']; ?></td>
            <td class="left"><?php echo $_['column_status']; ?></td>
            <td class="right"><?php echo $_['column_commission']; ?></td>
            <td class="right"><?php echo $_['column_orders']; ?></td>
            <td class="right"><?php echo $_['column_total']; ?></td>
            <td class="right"><?php echo $_['column_action']; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($affiliates) { ?>
          <?php foreach ($affiliates as $affiliate) { ?>
         <tr onmouseover="this.className='on';" onmouseout="this.className='';">
            <td class="left"><?php echo $affiliate['affiliate']; ?></td>
            <td class="left"><?php echo $affiliate['email']; ?></td>
            <td class="left"><?php echo $affiliate['status']; ?></td>
            <td class="right"><?php echo $affiliate['commission']; ?></td>
            <td class="right"><?php echo $affiliate['orders']; ?></td>
            <td class="right"><?php echo $affiliate['total']; ?></td>
            <td class="right"><?php foreach ($affiliate['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $_['text_no_results']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo UA('report/affiliate_commission'); ?>&filter_reset=1';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>
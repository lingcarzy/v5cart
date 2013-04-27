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
          <td><?php echo $_['entry_group']; ?>
            <select name="filter_group">
			 <?php echo form_select_option($groups, $filter_group, null, 'value', 'text');?>
            </select></td>
          <td><?php echo $_['entry_status']; ?>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $_['text_all_status']; ?></option>
			  <?php echo form_select_option($order_statuses, $filter_order_status_id, true);?>
            </select></td>
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $_['column_date_start']; ?></td>
            <td class="left"><?php echo $_['column_date_end']; ?></td>
            <td class="left"><?php echo $_['column_title']; ?></td>
            <td class="right"><?php echo $_['column_orders']; ?></td>
            <td class="right"><?php echo $_['column_total']; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr onmouseover="this.className='on';" onmouseout="this.className='';">
            <td class="left"><?php echo $order['date_start']; ?></td>
            <td class="left"><?php echo $order['date_end']; ?></td>
            <td class="left"><?php echo $order['title']; ?></td>
            <td class="right"><?php echo $order['orders']; ?></td>
            <td class="right"><?php echo $order['total']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?php echo $_['text_no_results']; ?></td>
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
	url = '<?php echo UA('report/sale_tax'); ?>&filter_reset=1';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').attr('value');
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
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
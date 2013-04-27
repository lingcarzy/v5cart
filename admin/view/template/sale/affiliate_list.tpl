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
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('form').attr('action', '<?php echo UA('sale/affiliate/approve'); ?>'); $('form').submit();" class="button"><?php echo $_['button_approve']; ?></a>
	  <a href="<?php echo UA('sale/affiliate/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').attr('action', '<?php echo UA('sale/affiliate/delete'); ?>'); $('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.email') { ?>
                <a href="<?php echo $sort_email; ?>" class="<?php echo $order; ?>"><?php echo $_['column_email']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_email; ?>"><?php echo $_['column_email']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_balance']; ?></td>
              <td class="left"><?php if ($sort == 'c.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo $order; ?>"><?php echo $_['column_status']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $_['column_status']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.approved') { ?>
                <a href="<?php echo $sort_approved; ?>" class="<?php echo $order; ?>"><?php echo $_['column_approved']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_approved; ?>"><?php echo $_['column_approved']; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $_['column_date_added']; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" /></td>
              <td>&nbsp;</td>
              <td><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $_['text_enabled']; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $_['text_disabled']; ?></option>
                  <?php } ?>
                </select></td>
              <td><select name="filter_approved">
                  <option value="*"></option>
                  <?php if ($filter_approved) { ?>
                  <option value="1" selected="selected"><?php echo $_['text_yes']; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $_['text_yes']; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_approved) && !$filter_approved) { ?>
                  <option value="0" selected="selected"><?php echo $_['text_no']; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $_['text_no']; ?></option>
                  <?php } ?>
                </select></td>
              <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $_['button_filter']; ?></a></td>
            </tr>
            <?php if ($affiliates) { ?>
            <?php foreach ($affiliates as $affiliate) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($affiliate['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $affiliate['name']; ?></td>
              <td class="left"><?php echo $affiliate['email']; ?></td>
              <td class="right"><?php echo $affiliate['balance']; ?></td>
              <td class="left"><?php echo $affiliate['status']; ?></td>
              <td class="left"><?php echo $affiliate['approved']; ?></td>
              <td class="left"><?php echo $affiliate['date_added']; ?></td>
              <td class="right"><?php foreach ($affiliate['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
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
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo UA('sale/affiliate'); ?>&filter_reset=1';

	var filter_name = $('input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_email = $('input[name=\'filter_email\']').attr('value');

	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_affiliate_group_id = $('select[name=\'filter_affiliate_group_id\']').attr('value');

	if (filter_affiliate_group_id != '*') {
		url += '&filter_affiliate_group_id=' + encodeURIComponent(filter_affiliate_group_id);
	}

	var filter_status = $('select[name=\'filter_status\']').attr('value');

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_approved = $('select[name=\'filter_approved\']').attr('value');

	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: '<?php echo UA('sale/affiliate/autocomplete'); ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.affiliate_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
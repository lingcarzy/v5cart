<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a>
	  </div>
    </div>
    <div class="content">
      <div class="vtabs"><a href="#tab-return"><?php echo $_['tab_return']; ?></a><a href="#tab-product"><?php echo $_['tab_product']; ?></a><a href="#tab-history"><?php echo $_['tab_return_history']; ?></a></div>
      <div id="tab-return" class="vtabs-content">
        <table class="form">
          <tr>
            <td><?php echo $_['text_return_id']; ?></td>
            <td><?php echo $return_id; ?></td>
          </tr>
          <?php if ($order) { ?>
          <tr>
            <td><?php echo $_['text_order_id']; ?></td>
            <td><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $_['text_order_id']; ?></td>
            <td><?php echo $order_id; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $_['text_date_ordered']; ?></td>
            <td><?php echo $date_ordered; ?></td>
          </tr>
          <?php if ($customer) { ?>
          <tr>
            <td><?php echo $_['text_customer']; ?></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $_['text_customer']; ?></td>
            <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $_['text_email']; ?></td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_telephone']; ?></td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <?php if ($return_status) { ?>
          <tr>
            <td><?php echo $_['text_return_status']; ?></td>
            <td id="return-status"><?php echo $return_status; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $_['text_date_added']; ?></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_date_modified']; ?></td>
            <td><?php echo $date_modified; ?></td>
          </tr>
        </table>
      </div>
      <div id="tab-product" class="vtabs-content">
        <table class="form">
          <tr>
            <td><?php echo $_['text_product']; ?></td>
            <td><?php echo $product; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_model']; ?></td>
            <td><?php echo $model; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_quantity']; ?></td>
            <td><?php echo $quantity; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_return_reason']; ?></td>
            <td><?php echo $return_reason; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_opened']; ?></td>
            <td><?php echo $opened; ?></td>
          </tr>
          <tr>
            <td><?php echo $_['text_return_action']; ?></td>
            <td><select name="return_action_id">
                <option value="0"></option>
				 <?php echo form_select_option($return_actions, $return_action_id, null, 'return_action_id', 'name'); ?>
              </select></td>
          </tr>
          <?php if ($comment) { ?>
          <tr>
            <td><?php echo $_['text_comment']; ?></td>
            <td><?php echo $comment; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div id="tab-history" class="vtabs-content">
        <div id="history"></div>
        <table class="form">
          <tr>
            <td><?php echo $_['entry_return_status']; ?></td>
            <td><select name="return_status_id">
				 <?php echo form_select_option($return_statuses, $return_status_id, null, 'return_status_id', 'name'); ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_notify']; ?></td>
            <td><input type="checkbox" name="notify" value="1" /></td>
          </tr>
          <tr>
            <td><?php echo $_['entry_comment']; ?></td>
            <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>
              <div style="margin-top: 10px; text-align: right;"><a onclick="history();" id="button-history" class="button"><?php echo $_['button_add_history']; ?></a></div></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'return_action_id\']').bind('change', function() {
	$.ajax({
		url: '<?php echo UA('sale/return/action'); ?>&return_id=<?php echo $return_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'return_action_id=' + this.value,
		beforeSend: function() {
			$('.success, .warning, .attention').remove();

			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $_['text_wait']; ?></div>');
		},
		success: function(json) {
			$('.success, .warning, .attention').remove();

			if (json['error']) {
				$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');

				$('.warning').fadeIn('slow');
			}

			if (json['success']) {
				$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');

				$('.success').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);

	return false;
});

$('#history').load('<?php echo UA('sale/return/history'); ?>&return_id=<?php echo $return_id; ?>');

function history() {
	$.ajax({
		url: '<?php echo UA('sale/return/history'); ?>&return_id=<?php echo $return_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'return_status_id=' + encodeURIComponent($('select[name=\'return_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-history').attr('disabled', true);
			$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $_['text_wait']; ?></div>');
		},
		complete: function() {
			$('#button-history').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#history').html(html);

			$('textarea[name=\'comment\']').val('');

			$('#return-status').html($('select[name=\'return_status_id\'] option:selected').text());
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<?php echo $footer; ?>
<div class="buttons">
  <div class="right">
	<input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<div id="pe_error"></div>
<script type="text/javascript"><!--
function paypal_express() {
	$.ajax({ 
		type: 'GET',
		url: '<?php echo U('payment/paypal_express/init', '', 'SSL');?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', 'true');
			$('#button-confirm').css('color', 'gray');
			$('#button-confirm').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}
			else if(json['error']) {
				$('#pe_error').html(json['error']);
				$('#button-confirm').bind('click', paypal_express);
				$('.wait').remove();
			}
		}
	});
}
	
$('#button-confirm').bind('click', paypal_express);
//--></script> 

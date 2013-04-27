<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
   <?php echo bread_crumbs(); ?>
  </div>
  <?php if (isset($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <div id="htabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a> <a href="#tab-payment"><?php echo $_['tab_payment']; ?></a>
        <?php if ($affiliate_id) { ?>
        <a href="#tab-transaction"><?php echo $_['tab_transaction']; ?></a>
        <?php } ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                <?php if (isset($error_firstname)) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                <?php if (isset($error_lastname)) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
                <?php if (isset($error_email)) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_telephone']; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                <?php if (isset($error_telephone)) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_fax']; ?></td>
              <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_company']; ?></td>
              <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_address_1']; ?></td>
              <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
                <?php if (isset($error_address_1)) { ?>
                <span class="error"><?php echo $error_address_1; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_address_2']; ?></td>
              <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_city']; ?></td>
              <td><input type="text" name="city" value="<?php echo $city; ?>" />
                <?php if (isset($error_city)) { ?>
                <span class="error"><?php echo $error_city ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span id="postcode-required" class="required">*</span> <?php echo $_['entry_postcode']; ?></td>
              <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
                <?php if (isset($error_postcode)) { ?>
                <span class="error"><?php echo $error_postcode ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_country']; ?></td>
              <td><select name="country_id">
                  <option value="false"><?php echo $_['text_select']; ?></option>
                  <?php echo form_select_option($countries, $country_id, true);?>
                </select>
                <?php if (isset($error_country)) { ?>
                <span class="error"><?php echo $error_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_zone']; ?></td>
              <td><select name="zone_id">
                </select>
                <?php if (isset($error_zone)) { ?>
                <span class="error"><?php echo $error_zone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $_['entry_code']; ?></td>
              <td><input type="code" name="code" value="<?php echo $code; ?>"  />
                <?php if (isset($error_code)) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_password']; ?></td>
              <td><input type="password" name="password" value="<?php echo $password; ?>"  />
                <?php if (isset($error_password)) { ?>
                <span class="error"><?php echo $error_password; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_confirm']; ?></td>
              <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                <?php if (isset($error_confirm)) { ?>
                <span class="error"><?php echo $error_confirm; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_status']; ?></td>
              <td><select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true); ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-payment">
          <table class="form">
            <tbody>
              <tr>
                <td><?php echo $_['entry_commission']; ?></td>
                <td><input type="text" name="commission" value="<?php echo $commission; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_tax']; ?></td>
                <td><input type="text" name="tax" value="<?php echo $tax; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_payment']; ?></td>
                <td><?php if ($payment == 'cheque') { ?>
                  <input type="radio" name="payment" value="cheque" id="cheque" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="cheque" id="cheque" />
                  <?php } ?>
                  <label for="cheque"><?php echo $_['text_cheque']; ?></label>
                  <?php if ($payment == 'paypal') { ?>
                  <input type="radio" name="payment" value="paypal" id="paypal" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="paypal" id="paypal" />
                  <?php } ?>
                  <label for="paypal"><?php echo $_['text_paypal']; ?></label>
                  <?php if ($payment == 'bank') { ?>
                  <input type="radio" name="payment" value="bank" id="bank" checked="checked" />
                  <?php } else { ?>
                  <input type="radio" name="payment" value="bank" id="bank" />
                  <?php } ?>
                  <label for="bank"><?php echo $_['text_bank']; ?></label></td>
              </tr>
            </tbody>
            <tbody id="payment-cheque" class="payment">
              <tr>
                <td><?php echo $_['entry_cheque']; ?></td>
                <td><input type="text" name="cheque" value="<?php echo $cheque; ?>" /></td>
              </tr>
            </tbody>
            <tbody id="payment-paypal" class="payment">
              <tr>
                <td><?php echo $_['entry_paypal']; ?></td>
                <td><input type="text" name="paypal" value="<?php echo $paypal; ?>" /></td>
              </tr>
            </tbody>
            <tbody id="payment-bank" class="payment">
              <tr>
                <td><?php echo $_['entry_bank_name']; ?></td>
                <td><input type="text" name="bank_name" value="<?php echo $bank_name; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_bank_branch_number']; ?></td>
                <td><input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_bank_swift_code']; ?></td>
                <td><input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_bank_account_name']; ?></td>
                <td><input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_bank_account_number']; ?></td>
                <td><input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" /></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php if ($affiliate_id) { ?>
        <div id="tab-transaction">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_description']; ?></td>
              <td><input type="text" name="description" value="" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_amount']; ?></td>
              <td><input type="text" name="amount" value="" /></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: right;"><a id="button-reward" class="button" onclick="addTransaction();"><span><?php echo $_['button_add_transaction']; ?></span></a></td>
            </tr>
          </table>
          <div id="transaction"></div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: '<?php echo UA('common/ajax/country'); ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $_['text_select']; ?></option>';
			
			if (json != '' && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $_['text_none']; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'payment\']').bind('change', function() {
	$('.payment').hide();
	
	$('#payment-' + this.value).show();
});

$('input[name=\'payment\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#transaction .pagination a').live('click', function() {
	$('#transaction').load(this.href);
	
	return false;
});			

$('#transaction').load('<?php echo UA('sale/affiliate/transaction'); ?>&affiliate_id=<?php echo $affiliate_id; ?>');

function addTransaction() {
	$.ajax({
		url: '<?php echo UA('sale/affiliate/transaction'); ?>&affiliate_id=<?php echo $affiliate_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-transaction').attr('disabled', true);
			$('#transaction').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $_['text_wait']; ?></div>');
		},
		complete: function() {
			$('#button-transaction').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#transaction').html(html);
			
			$('#tab-transaction input[name=\'amount\']').val('');
			$('#tab-transaction input[name=\'description\']').val('');
		}
	});
}
//--></script> 
<script type="text/javascript"><!--
$('.htabs a').tabs();
//--></script> 
<?php echo $footer; ?>
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
      <div id="htabs" class="htabs"><a href="#tab-general"><?php echo $_['tab_general']; ?></a>
        <?php if ($customer_id) { ?>
        <a href="#tab-transaction"><?php echo $_['tab_transaction']; ?></a><a href="#tab-reward"><?php echo $_['tab_reward']; ?></a>
        <?php } ?>
        <a href="#tab-ip"><?php echo $_['tab_ip']; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="vtabs" class="vtabs"><a href="#tab-customer"><?php echo $_['tab_general']; ?></a>
            <?php $address_row = 1; ?>
            <?php foreach ($addresses as $address) { ?>
            <a href="#tab-address-<?php echo $address_row; ?>" id="address-<?php echo $address_row; ?>"><?php echo $_['tab_address'] . ' ' . $address_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#address-<?php echo $address_row; ?>').remove(); $('#tab-address-<?php echo $address_row; ?>').remove(); return false;" /></a>
            <?php $address_row++; ?>
            <?php } ?>
            <span id="address-add"><?php echo $_['button_add_address']; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addAddress();" /></span></div>
          <div id="tab-customer" class="vtabs-content">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>
                <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" data-rule-required="true" data-rule-rangelength="2,32" />
                  <?php if (isset($error_firstname)) { ?>
                  <span class="error"><?php echo $error_firstname; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>
                <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" data-rule-required="true" data-rule-rangelength="2,32" />
                  <?php if (isset($error_lastname)) { ?>
                  <span class="error"><?php echo $error_lastname; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_email']; ?></td>
                <td><input type="text" name="email" value="<?php echo $email; ?>" data-rule-required="true" data-rule-maxlength="96" data-rule-email="true" />
                  <?php if (isset($error_email)) { ?>
                  <span class="error"><?php echo $error_email; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_telephone']; ?></td>
                <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" data-rule-required="true" data-rule-rangelength="3,32"/>
                  <?php if (isset($error_telephone)) { ?>
                  <span class="error"><?php echo $error_telephone; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_fax']; ?></td>
                <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_password']; ?></td>
                <td><input type="password" name="password" id="password" value="<?php echo $password; ?>"  data-rule-rangelength="4,20" />
                  <?php if (isset($error_password)) { ?>
                  <span class="error"><?php echo $error_password; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_confirm']; ?></td>
                <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" data-rule-equalTo="#password" />
                  <?php if (isset($error_confirm)) { ?>
                  <span class="error"><?php echo $error_confirm; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_newsletter']; ?></td>
                <td><select name="newsletter">
                    <?php if ($newsletter) { ?>
                    <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                    <option value="0"><?php echo $_['text_disabled']; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $_['text_enabled']; ?></option>
                    <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_customer_group']; ?></td>
                <td><select name="customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_status']; ?></td>
                <td><select name="status">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $_['text_enabled']; ?></option>
                    <option value="0"><?php echo $_['text_disabled']; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $_['text_enabled']; ?></option>
                    <option value="0" selected="selected"><?php echo $_['text_disabled']; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
            </table>
          </div>
          <?php $address_row = 1; ?>
          <?php foreach ($addresses as $address) { ?>
          <div id="tab-address-<?php echo $address_row; ?>" class="vtabs-content">
            <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" />
                  <?php 
				  $e = 'error_address_firstname_' . $address_row;
				  if (isset($$e)) { ?>
                  <span class="error"><?php echo $$e; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" />
                  <?php 
				  $e = 'error_address_lastname_' . $address_row;
				  if (isset($$e)) { ?>
                  <span class="error"><?php echo $$e; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $_['entry_company']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" /></td>
              </tr>
              <tr class="company-id-display">
                <td><?php echo $_['entry_company_id']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][company_id]" value="<?php echo $address['company_id']; ?>" /></td>
              </tr>
              <tr class="tax-id-display">
                <td><?php echo $_['entry_tax_id']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][tax_id]" value="<?php echo $address['tax_id']; ?>" />
					<?php 
						$e = 'error_address_tax_id_' . $address_row;
						if (isset($$e)) { ?>
						<span class="error"><?php echo $$e; ?></span>
					<?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_address_1']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" />
					<?php 
						$e = 'error_address_address_1_' . $address_row;
						if (isset($$e)) { ?>
						<span class="error"><?php echo $$e; ?></span>
					<?php } ?>
					</td>
              </tr>
              <tr>
                <td><?php echo $_['entry_address_2']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_city']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" />
					<?php 
						$e = 'error_address_city_' . $address_row;
						if (isset($$e)) { ?>
						<span class="error"><?php echo $$e; ?></span>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> <?php echo $_['entry_postcode']; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" /></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_country']; ?></td>
                <td><select name="address[<?php echo $address_row; ?>][country_id]" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>');">
                    <option value=""><?php echo $_['text_select']; ?></option>
					<?php echo form_select_option($countries, $address['country_id'], true);?>
                  </select>
					<?php 
						$e = 'error_address_country_' . $address_row;
						if (isset($$e)) { ?>
						<span class="error"><?php echo $$e; ?></span>
					<?php } ?>
					</td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $_['entry_zone']; ?></td>
                <td><select name="address[<?php echo $address_row; ?>][zone_id]">
                  </select>
					<?php 
						$e = 'error_address_zone_' . $address_row;
						if (isset($$e)) { ?>
						<span class="error"><?php echo $$e; ?></span>
					<?php } ?>
				</td>
              </tr>
              <tr>
                <td><?php echo $_['entry_default']; ?></td>
                <td><?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                  <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" /></td>
                <?php } else { ?>
                <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
                  </td>
                <?php } ?>
              </tr>
            </table>
          </div>
          <?php $address_row++; ?>
          <?php } ?>
        </div>
        <?php if ($customer_id) { ?>
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
              <td colspan="2" style="text-align: right;"><a id="button-transaction" class="button" onclick="addTransaction();"><span><?php echo $_['button_add_transaction']; ?></span></a></td>
            </tr>
          </table>
          <div id="transaction"></div>
        </div>
        <div id="tab-reward">
          <table class="form">
            <tr>
              <td><?php echo $_['entry_description']; ?></td>
              <td><input type="text" name="description" value="" /></td>
            </tr>
            <tr>
              <td><?php echo $_['entry_points']; ?></td>
              <td><input type="text" name="points" value="" /></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: right;"><a id="button-reward" class="button" onclick="addRewardPoints();"><span><?php echo $_['button_add_reward']; ?></span></a></td>
            </tr>
          </table>
          <div id="reward"></div>
        </div>
        <?php } ?>
        <div id="tab-ip">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $_['column_ip']; ?></td>
                <td class="right"><?php echo $_['column_total']; ?></td>
                <td class="left"><?php echo $_['column_date_added']; ?></td>
                <td class="right"><?php echo $_['column_action']; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($ips) { ?>
              <?php foreach ($ips as $ip) { ?>
              <tr>
                <td class="left"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
                <td class="right"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
                <td class="left"><?php echo $ip['date_added']; ?></td>
                <td class="right"><?php if ($ip['blacklist']) { ?>
                  <b>[</b> <a id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="removeBlacklist('<?php echo $ip['ip']; ?>');"><?php echo $_['text_remove_blacklist']; ?></a> <b>]</b>
                  <?php } else { ?>
                  <b>[</b> <a id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="addBlacklist('<?php echo $ip['ip']; ?>');"><?php echo $_['text_add_blacklist']; ?></a> <b>]</b>
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
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'customer_group_id\']').live('change', function() {
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('.company-id-display').show();
		} else {
			$('.company-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('.tax-id-display').show();
		} else {
			$('.tax-id-display').hide();
		}
	}
});

$('select[name=\'customer_group_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
function country(element, index, zone_id) {
	if (element.value == '') return;
	$.ajax({
		url: '<?php echo UA('common/ajax/country'); ?>&country_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'address[' + index + '][country_id]\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required' + index).show();
			} else {
				$('#postcode-required' + index).hide();
			}
			
			html = '<option value=""><?php echo $_['text_select']; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == zone_id) {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $_['text_none']; ?></option>';
			}
			
			$('select[name=\'address[' + index + '][zone_id]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name$=\'[country_id]\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {	
	html  = '<div id="tab-address-' + address_row + '" class="vtabs-content" style="display: none;">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';
	html += '  <table class="form">'; 
	html += '    <tr>';
    html += '	   <td><span class="required">*</span> <?php echo $_['entry_firstname']; ?></td>';
    html += '	   <td><input type="text" name="address[' + address_row + '][firstname]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $_['entry_lastname']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][lastname]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $_['entry_company']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][company]" value="" /></td>';
    html += '    </tr>';	
    html += '    <tr class="company-id-display">';
    html += '      <td><?php echo $_['entry_company_id']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][company_id]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr class="tax-id-display">';
    html += '      <td><?php echo $_['entry_tax_id']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][tax_id]" value="" /></td>';
    html += '    </tr>';			
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $_['entry_address_1']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][address_1]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $_['entry_address_2']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][address_2]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $_['entry_city']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][city]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span id="postcode-required' + address_row + '" class="required">*</span> <?php echo $_['entry_postcode']; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][postcode]" value="" /></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $_['entry_country']; ?></td>';
    html += '      <td><select name="address[' + address_row + '][country_id]" onchange="country(this, \'' + address_row + '\', \'0\');">';
    html += '         <option value=""><?php echo $_['text_select']; ?></option>';
    <?php foreach ($countries as $id => $name) { ?>
    html += '         <option value="<?php echo $id; ?>"><?php echo addslashes($name); ?></option>';
    <?php } ?>
    html += '      </select></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $_['entry_zone']; ?></td>';
    html += '      <td><select name="address[' + address_row + '][zone_id]"><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><?php echo $_['entry_default']; ?></td>';
    html += '      <td><input type="radio" name="address[' + address_row + '][default]" value="1" /></td>';
    html += '    </tr>';
    html += '  </table>';
    html += '</div>';
	
	$('#tab-general').append(html);
	
	$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');	
	
	$('#address-add').before('<a href="#tab-address-' + address_row + '" id="address-' + address_row + '"><?php echo $_['tab_address']; ?> ' + address_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address-' + address_row + '\').remove(); $(\'#tab-address-' + address_row + '\').remove(); return false;" /></a>');
		 
	$('.vtabs a').tabs();
	
	$('#address-' + address_row).trigger('click');
	
	address_row++;
}
//--></script> 
<script type="text/javascript"><!--
$('#transaction .pagination a').live('click', function() {
	$('#transaction').load(this.href);
	
	return false;
});			

$('#transaction').load('<?php echo UA('sale/customer/transaction'); ?>&customer_id=<?php echo $customer_id; ?>');

function addTransaction() {
	$.ajax({
		url: '<?php echo UA('sale/customer/transaction'); ?>&customer_id=<?php echo $customer_id; ?>',
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
$('#reward .pagination a').live('click', function() {
	$('#reward').load(this.href);
	
	return false;
});			

$('#reward').load('<?php echo UA('sale/customer/reward'); ?>&customer_id=<?php echo $customer_id; ?>');

function addRewardPoints() {
	$.ajax({
		url: '<?php echo UA('sale/customer/reward'); ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-reward').attr('disabled', true);
			$('#reward').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $_['text_wait']; ?></div>');
		},
		complete: function() {
			$('#button-reward').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#reward').html(html);
								
			$('#tab-reward input[name=\'points\']').val('');
			$('#tab-reward input[name=\'description\']').val('');
		}
	});
}

function addBlacklist(ip) {
	$.ajax({
		url: '<?php echo UA('sale/customer/addblacklist'); ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(ip),
		beforeSend: function() {
			$('.success, .warning').remove();
			
			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> Please wait!</div>');			
		},
		complete: function() {
			$('.attention').remove();
		},			
		success: function(json) {
			if (json['error']) {
				 $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
						
			if (json['success']) {
                $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#' + ip.replace(/\./g, '-')).replaceWith('<a id="' + ip.replace(/\./g, '-') + '" onclick="removeBlacklist(\'' + ip + '\');"><?php echo $_['text_remove_blacklist']; ?></a>');
			}
		}
	});	
}

function removeBlacklist(ip) {
	$.ajax({
		url: '<?php echo UA('sale/customer/removeblacklist'); ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(ip),
		beforeSend: function() {
			$('.success, .warning').remove();
			
			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> Please wait!</div>');				
		},
		complete: function() {
			$('.attention').remove();
		},			
		success: function(json) {
			if (json['error']) {
				 $('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
				 $('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#' + ip.replace(/\./g, '-')).replaceWith('<a id="' + ip.replace(/\./g, '-') + '" onclick="addBlacklist(\'' + ip + '\');"><?php echo $_['text_add_blacklist']; ?></a>');
			}
		}
	});	
};
//--></script> 
<script type="text/javascript"><!--
$('.htabs a').tabs();
$('.vtabs a').tabs();
//--></script> 
<?php echo $footer; ?>
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
      <h1><img src="view/image/tax.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	  <a href="<?php echo UA('localisation/tax_class'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_title']; ?></td>
            <td><input type="text" name="title" value="<?php echo $title; ?>" />
              <?php if (isset($error_title)) { ?>
              <span class="error"><?php echo $error_title; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_description']; ?></td>
            <td><input type="text" name="description" value="<?php echo $description; ?>" />
              <?php if (isset($error_description)) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
          </tr>
        </table>
        <br />
        <table id="tax-rule" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $_['entry_rate']; ?></td>
              <td class="left"><?php echo $_['entry_based']; ?></td>
              <td class="left"><?php echo $_['entry_priority']; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $tax_rule_row = 0; ?>
          <?php foreach ($tax_rules as $tax_rule) { ?>
          <tbody id="tax-rule-row<?php echo $tax_rule_row; ?>">
            <tr>
              <td class="left"><select name="tax_rule[<?php echo $tax_rule_row; ?>][tax_rate_id]">
                  <?php foreach ($tax_rates as $tax_rate) { ?>
                  <?php  if ($tax_rate['tax_rate_id'] == $tax_rule['tax_rate_id']) { ?>
                  <option value="<?php echo $tax_rate['tax_rate_id']; ?>" selected="selected"><?php echo $tax_rate['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo $tax_rate['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="tax_rule[<?php echo $tax_rule_row; ?>][based]">
                  <?php  if ($tax_rule['based'] == 'shipping') { ?>
                  <option value="shipping" selected="selected"><?php echo $_['text_shipping']; ?></option>
                  <?php } else { ?>
                  <option value="shipping"><?php echo $_['text_shipping']; ?></option>
                  <?php } ?>
                  <?php  if ($tax_rule['based'] == 'payment') { ?>
                  <option value="payment" selected="selected"><?php echo $_['text_payment']; ?></option>
                  <?php } else { ?>
                  <option value="payment"><?php echo $_['text_payment']; ?></option>
                  <?php } ?>  
                  <?php  if ($tax_rule['based'] == 'store') { ?>
                  <option value="store" selected="selected"><?php echo $_['text_store']; ?></option>
                  <?php } else { ?>
                  <option value="store"><?php echo $_['text_store']; ?></option>
                  <?php } ?>                                    
                </select></td>
              <td class="left"><input type="text" name="tax_rule[<?php echo $tax_rule_row; ?>][priority]" value="<?php echo $tax_rule['priority']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#tax-rule-row<?php echo $tax_rule_row; ?>').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>
            </tr>
          </tbody>
          <?php $tax_rule_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addRule();" class="button"><?php echo $_['button_add_rule']; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var tax_rule_row = <?php echo $tax_rule_row; ?>;

function addRule() {
	html  = '<tbody id="tax-rule-row' + tax_rule_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="tax_rule[' + tax_rule_row + '][tax_rate_id]">';
    <?php foreach ($tax_rates as $tax_rate) { ?>
    html += '      <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo addslashes($tax_rate['name']); ?></option>';
    <?php } ?>
    html += '    </select></td>';
	html += '    <td class="left"><select name="tax_rule[' + tax_rule_row + '][based]">';
    html += '      <option value="shipping"><?php echo $_['text_shipping']; ?></option>';
    html += '      <option value="payment"><?php echo $_['text_payment']; ?></option>';
    html += '      <option value="store"><?php echo $_['text_store']; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="tax_rule[' + tax_rule_row + '][priority]" value="" size="1" /></td>';
	html += '    <td class="left"><a onclick="$(\'#tax-rule-row' + tax_rule_row + '\').remove();" class="button"><?php echo $_['button_remove']; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#tax-rule > tfoot').before(html);
	
	tax_rule_row++;
}
//--></script> 
<?php echo $footer; ?>
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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $_['entry_name']; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="customer_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php
				$e = 'error_name_' . $language['language_id'] ;
			  if (isset($$e)) { ?>
              <span class="error"><?php echo $$e; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <?php foreach ($languages as $language) { ?>
          <tr>
            <td><?php echo $_['entry_description']; ?></td>
            <td><textarea name="customer_group_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['description'] : ''; ?></textarea>
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $_['entry_approval']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'approval', $approval); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_company_id_display']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'company_id_display', $company_id_display); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_company_id_required']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'company_id_required', $company_id_required); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax_id_display']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'tax_id_display', $tax_id_display); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_tax_id_required']; ?></td>
            <td>
			<?php echo form_radio($_['option_yesno'], 'tax_id_required', $tax_id_required); ?>
			</td>
          </tr>
          <tr>
            <td><?php echo $_['entry_sort_order']; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
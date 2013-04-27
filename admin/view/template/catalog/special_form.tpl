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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><span><?php echo $_['button_save']; ?></span></a>
	  <a href="<?php echo UA('catalog/specials'); ?>" class="button"><span><?php echo $_['button_cancel']; ?></span></a>
	  </div>
    </div>
	
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $_['text_product']; ?></td>
            <td><?php echo $special['name']?></td>
          </tr>
		  <tr>
            <td><?php echo $_['column_customer_group']; ?></td>
            <td>
				<select name="customer_group_id">
				<?php echo form_select_option($customer_group, $special['customer_group_id'], null, 'customer_group_id', 'name'); ?>
				</select>
			</td>
          </tr>
		  <tr>
            <td><?php echo $_['column_priority']; ?></td>
            <td><input type="text" name="priority" value="<?php echo $special['priority']; ?>" /></td>
          </tr>
		  <tr>
            <td><?php echo $_['column_price']; ?></td>
            <td><input type="text" name="price" value="<?php echo $special['price']; ?>" /></td>
          </tr>
		  <tr>
            <td><?php echo $_['column_date_start']; ?></td>
            <td><input type="text" name="date_start" value="<?php echo $special['date_start']; ?>" class="date" /></td>
          </tr>
		  <tr>
            <td><?php echo $_['column_date_end']; ?></td>
            <td><input type="text" name="date_end" value="<?php echo $special['date_end']; ?>" class="date" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
//--></script> 
<?php echo $footer; ?>
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
		<a href="<?php echo UA('sale/coupon/insert'); ?>" class="button"><?php echo $_['button_insert']; ?></a>
		<a onclick="$('#form').submit();" class="button"><?php echo $_['button_delete']; ?></a>
	  </div>
    </div>
	
    <div class="content">
      <form action="<?php echo UA('sale/coupon/delete'); ?>" method="post" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;">
				<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
			  </td>
              <td class="left">
				<?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo $order; ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?>
			  </td>
              <td class="left">
				<?php if ($sort == 'code') { ?>
                <a href="<?php echo $sort_code; ?>" class="<?php echo $order; ?>"><?php echo $_['column_code']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_code; ?>"><?php echo $_['column_code']; ?></a>
                <?php } ?>
			  </td>
              <td class="right">
				<?php if ($sort == 'discount') { ?>
                <a href="<?php echo $sort_discount; ?>" class="<?php echo $order; ?>"><?php echo $_['column_discount']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_discount; ?>"><?php echo $_['column_discount']; ?></a>
                <?php } ?>
			  </td>
              <td class="left">
				<?php if ($sort == 'date_start') { ?>
                <a href="<?php echo $sort_date_start; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_start']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_start; ?>"><?php echo $_['column_date_start']; ?></a>
                <?php } ?>
			  </td>
              <td class="left">
				<?php if ($sort == 'date_end') { ?>
                <a href="<?php echo $sort_date_end; ?>" class="<?php echo $order; ?>"><?php echo $_['column_date_end']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_end; ?>"><?php echo $_['column_date_end']; ?></a>
                <?php } ?>
			  </td>
              <td class="left">
				<?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo $order; ?>"><?php echo $_['column_status']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $_['column_status']; ?></a>
                <?php } ?>
			  </td>
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($coupons) { ?>
            <?php foreach ($coupons as $coupon) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" />
              </td>
              <td class="left"><?php echo $coupon['name']; ?></td>
              <td class="left"><?php echo $coupon['code']; ?></td>
              <td class="right"><?php echo $coupon['discount']; ?></td>
              <td class="left"><?php echo $coupon['date_start']; ?></td>
              <td class="left"><?php echo $coupon['date_end']; ?></td>
              <td class="left"><?php echo $coupon['status']; ?></td>
              <td class="right">
				<?php foreach ($coupon['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?>
			  </td>
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
<?php echo $footer; ?>
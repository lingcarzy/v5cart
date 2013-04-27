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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a onclick="$('form').submit();" class="button"><span><?php echo $_['button_delete']; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/specials/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td><?php echo $_['column_image']; ?></td>
              <td class="left"><?php echo $_['column_product']; ?></td>
            <td class="left"><?php if ($sort == 'ps.customer_group_id') { ?>
                <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $_['column_customer_group']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer_group; ?>"><?php echo $_['column_customer_group']; ?></a>
                <?php } ?></td>
			<td class="left"><?php echo $_['column_priority']; ?></td>
			<td class="left"><?php echo $_['column_price']; ?></td>
			<td class="left"><?php if ($sort == 'ps.date_start') { ?>
                <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $_['column_date_start']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_start; ?>"><?php echo $_['column_date_start']; ?></a>
                <?php } ?></td>
			  <td class="left"><?php if ($sort == 'ps.date_end') { ?>
                <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $_['column_date_end']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_end; ?>"><?php echo $_['column_date_end']; ?></a>
                <?php } ?></td>			  
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($specials) { ?>
            <?php foreach ($specials as $special) { ?>
			<?php
				$time = 0;
				if ($special['date_end'] != '0000-00-00') {
					$time = strtotime($special['date_end']) + 86400;
				}				
			?>
            <tr <?php if ($time > 0 && $cur_time > $time){?>class="bgray"<?php }else{?>onmouseover="this.className='on';" onmouseout="this.className='';"<?php } ?>>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $special['product_special_id']; ?>" />
              </td>
			  <td class="center">
			  <a class="powerfloat" href="<?php echo $special['image']; ?>">
			  <img src="<?php echo $special['image']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;width:40px;" />
			  </a>
			  </td>
              <td class="left"><span style="color:blue"><?php echo $special['model']; ?></span><br><?php echo $special['name']; ?></td>
              <td class="left"><?php echo $special['customer_group']; ?></td>
			  <td class="right"><?php echo $special['priority']; ?></td>
			  <td class="right"><del><?php echo number_format($special['list_price'], 2); ?></del><br>
				<span style="color: #b00;"><?php echo number_format($special['price'], 2); ?></span></td>
			  <td class="right"><?php echo $special['date_start']; ?></td>
			  <td class="right"><?php echo $special['date_end']; ?></td>
              <td class="right"><?php foreach ($special['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
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
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script>
$(".powerfloat").powerFloat({targetMode: "ajax", targetAttr: "href", position: "3-4" });
</script>
<?php echo $footer; ?>
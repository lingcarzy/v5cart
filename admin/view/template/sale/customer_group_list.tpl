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
	  <a href="<?php echo $insert; ?>" class="button"><?php echo $_['button_insert']; ?></a>
	  <a onclick="$('form').submit();" class="button"><?php echo $_['button_delete']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'cgd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $_['column_name']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $_['column_name']; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'cg.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $_['column_sort_order']; ?></a>
                <?php } ?></td>                
              <td class="right"><?php echo $_['column_action']; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($customer_groups) { ?>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($customer_group['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $customer_group['name']; ?></td>
              <td class="right"><?php echo $customer_group['sort_order']; ?></td>
              <td class="right"><?php foreach ($customer_group['action'] as $action) { ?>
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
<?php echo $footer; ?> 
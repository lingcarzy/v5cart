<?php echo $header; ?>
<div id="content">
   <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /><?php echo $_['heading_title']; ?></h1>
	  <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $_['button_save']; ?></a>
	   <a href="<?php echo UA('catalog/product_group'); ?>" class="button"><?php echo $_['button_cancel']; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" id="form">		
        <table class="form">
			<tr>
			  <td><span class="required">*</span> Ref ID</td>
			  <td><input type="text" name="ref_id" value="<?php echo $ref_id;?>" size="40" data-rule-required="true" /></td>
			</tr>
            <tr>
			  <td><span class="required">*</span> Title</td>
			  <td><input type="text" name="title" value="<?php echo $title;?>" size="80" data-rule-required="true" />
			  </td>
            </tr>
			<tr>
			  <td>Status</td>
			<td>
				<select name="status">
				<?php echo form_select_option($_['option_statuses'], $status, true);?>
				</select>
			</td>
            </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
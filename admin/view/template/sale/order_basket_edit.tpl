<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?> :: Edit
  </div>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /><?php echo $_['heading_title'];?></h1>
      <div class="buttons">
	  <a onclick="$('form').submit();" class="button"><span>Save</span></a>
	  <a href="<?php echo UA('sale/order_basket'); ?>" class="button"><span>Cancel</span></a>
	  </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		   <tr><td>ID</td><td><?php echo $basket['basket_id']?></td></tr>
          <tr><td>Status</td><td><input type="text" name="status" value="<?php echo $basket['status']?>"></td></tr>
		  <tr><td>Remark</td><td><input type="text" name="remark" value="<?php echo $basket['remark']; ?>" size="80"></td></tr>
        </table>
      </form>
    </div>
  </div>
</div>

<?php echo $footer; ?>
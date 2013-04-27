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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <a onclick="$('#form').submit();" class="button"><span>Save</span></a>
	  <a href="<?php echo UA('catalog/supplier'); ?>" class="button"><span>Cancel</span></a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><span class="required">*</span> Name</td>
              <td><input type="text" name="name" value="<?php echo isset($supplier) ? $supplier['name'] : ''?>" size="50" data-rule-required="true" /></td>
            </tr>
			<tr>
              <td> Concact</td>
              <td><input type="text" name="contact" value="<?php echo isset($supplier) ? $supplier['contact'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> Address</td>
              <td><input type="text" name="address" value="<?php echo isset($supplier) ? $supplier['address'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> Telephone</td>
              <td><input type="text" name="telphone" value="<?php echo isset($supplier) ? $supplier['telphone'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> Email</td>
              <td><input type="text" name="email" value="<?php echo isset($supplier) ? $supplier['email'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> Website</td>
              <td><input type="text" name="url" value="<?php echo isset($supplier) ? $supplier['url'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> IM</td>
              <td><input type="text" name="im" value="<?php echo isset($supplier) ? $supplier['im'] : ''?>" size="50" /></td>
            </tr>
			<tr>
              <td> Remark</td>
              <td><input type="text" name="remark" value="<?php echo isset($supplier) ? $supplier['remark'] : ''?>" size="50" /></td>
            </tr>
          </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>
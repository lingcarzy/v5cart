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
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons">
	  <input type="text" name="keyword" value="<?=$keyword?>">
	  <a onclick="filter();" class="button"><span>Filter</span></a>
	  | <a href="<?php echo UA('catalog/supplier/insert'); ?>" class="button"><span><?php echo $_['button_insert'];?></span></a>
	  <a onclick="$('form').submit();" class="button"><span><?php echo $_['button_delete'];?></span></a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo UA('catalog/supplier/delete'); ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left">
                <a href="<?php echo $sort_url; ?>" class="<?php echo $order; ?>">Name</a>
               </td>
			  <td>Contact</td>
              <td>Addr.</td>
			  <td>Telephone</td>
			  <td>Email</td>
			  <td>IM</td>
			  <td>Remark</td>
              <td width="60" class="right">Action</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($suppliers) { ?>
            <?php foreach ($suppliers as $supplier) { ?>
            <tr onmouseover="this.className='on';" onmouseout="this.className='';">
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" />
              </td>
              <td class="left">
			  <?php if (!empty($supplier['url'])) {?>
				<a href="<?php echo $supplier['url']?>" target="_blank"><?php echo $supplier['name']; ?></a>
			  <?php } else {?>
				<?php echo $supplier['name']; ?>
			  <?php } ?>
			  </td>
			  <td class="left"><?php echo $supplier['contact']; ?></td>
			  <td class="left"><?php echo $supplier['address']; ?></td>
			  <td class="left"><?php echo $supplier['telphone']; ?></td>
			  <td class="left"><?php echo $supplier['email']; ?></td>
			  <td class="left"><?php echo $supplier['im']; ?></td>
			  <td class="left"><?php echo $supplier['remark']; ?></td>
			  <td class="right">[<a href="<?php echo UA('catalog/supplier/update');?>&supplier_id=<?php echo $supplier['supplier_id'];?>">Edit</a>]</td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10">No result!</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});

function filter() {
	url = '<?php echo UA('catalog/supplier');?>&filter_reset=1';	
	var keyword = $('input[name=\'keyword\']').attr('value');	
	if (keyword) {
		url += '&keyword=' + encodeURIComponent(keyword);
	}
	location = url;
}
//--></script> 
<?php echo $footer; ?>
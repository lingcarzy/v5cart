<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
   <?php echo bread_crumbs(); ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $_['column_customer']; ?></td>
            <td class="left"><?php echo $_['column_email']; ?></td>
            <td class="left"><?php echo $_['column_customer_group']; ?></td>
            <td class="left"><?php echo $_['column_status']; ?></td>            
            <td class="right"><?php echo $_['column_total']; ?></td>
            <td class="right"><?php echo $_['column_action']; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($customers) { ?>
          <?php foreach ($customers as $customer) { ?>
         <tr onmouseover="this.className='on';" onmouseout="this.className='';">
            <td class="left"><?php echo $customer['customer']; ?></td>
            <td class="left"><?php echo $customer['email']; ?></td>
            <td class="left"><?php echo $customer['customer_group']; ?></td>
            <td class="left"><?php echo $customer['status']; ?></td>
            <td class="right"><?php echo $customer['total']; ?></td>
            <td class="right"><?php foreach ($customer['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>            
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $_['text_no_results']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
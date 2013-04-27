<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php echo bread_crumbs(); ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $_['heading_title']; ?></h1>
      <div class="buttons"><a href="<?php echo $reset; ?>" class="button"><?php echo $_['button_reset']; ?></a></div>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $_['column_name']; ?></td>
            <td class="left"><?php echo $_['column_model']; ?></td>
            <td class="right"><?php echo $_['column_viewed']; ?></td>
            <td class="right"><?php echo $_['column_percent']; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <tr onmouseover="this.className='on';" onmouseout="this.className='';">
            <td class="left"><?php echo $product['name']; ?></td>
            <td class="left"><?php echo $product['model']; ?></td>
            <td class="right"><?php echo $product['viewed']; ?></td>
            <td class="right"><?php echo $product['percent']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $_['text_no_results']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
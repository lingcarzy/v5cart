<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
  <thead>
    <tr>
      <td class="left"><?php echo $_['column_date_added']; ?></td>
      <td class="left"><?php echo $_['column_description']; ?></td>
      <td class="right"><?php echo $_['column_amount']; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($transactions) { ?>
    <?php foreach ($transactions as $transaction) { ?>
    <tr>
      <td class="left"><?php echo $transaction['date_added']; ?></td>
      <td class="left"><?php echo $transaction['description']; ?></td>
      <td class="right"><?php echo $transaction['amount']; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <td class="right"><b><?php echo $_['text_balance']; ?></b></td>
      <td class="right"><?php echo $balance; ?></td>
    </tr>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="3"><?php echo $_['text_no_results']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>

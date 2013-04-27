<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<title>SKUs</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
</head>
<body>
<div id="content">
    <div class="content">
        <table class="list">
          <thead>
            <tr>
              <td>Image</td>
			  <td>Model</td>
              <td>Name</td>
              <td>Stock</td>
              <td>Sale Price</td>
              <td>Purchase Price</td>
              <td>Quantity</td>
			  <td>Supplier</td>
			 
			  <td>Remark</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($skus) { ?>
            <?php foreach ($skus as $s) { ?>
            <tr>
              <td><img src="<?php echo $s['image']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /><br><?php echo $s['sku']; ?></td>
			  <td><?php echo $s['model']; ?></td>
              <td><?php echo $s['name']; ?></td>
              <td><?php echo $s['stock']; ?></td>
              <td><?php echo round($s['price'] ,2); ?></td>
              <td><?php echo round($s['cost'] ,2); ?></td>
              <td><?php echo $s['qty']; ?></td>
			  <td><?php echo $s['supplier']; ?></td>			 
			  <td></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="18"></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
</div>
</body>
</html>
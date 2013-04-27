<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
       <ul>
        <?php foreach ($categories['p'] as $cate_id) { ?>
        <li>
          <?php if ($cate_id == $category_id) { ?>
          <a href="<?php echo $categories[$cate_id]['link']; ?>" class="active"><?php echo $categories[$cate_id]['name'] . (C('config_product_count') ? ' (' . $categories[$cate_id]['total'] . ')' : ''); ?></a>
          <?php } else { ?>
          <a href="<?php echo $categories[$cate_id]['link']; ?>"><?php echo $categories[$cate_id]['name'] . (C('config_product_count') ? ' (' . $categories[$cate_id]['total'] . ')' : ''); ?></a>
          <?php } ?>
          <?php if (isset($categories[$cate_id]['sub'])) { ?>
          <ul>
            <?php foreach (explode(',', $categories[$cate_id]['sub']) as $sub) { ?>
            <li>
              <?php if ($sub == $child_id) { ?>
              <a href="<?php echo $categories[$sub]['link']; ?>" class="active"> - <?php echo $categories[$sub]['name'] . (C('config_product_count') ? ' (' . $categories[$sub]['total'] . ')' : ''); ?></a>
              <?php } else { ?>
              <a href="<?php echo $categories[$sub]['link']; ?>"> - <?php echo $categories[$sub]['name'] . (C('config_product_count') ? ' (' . $categories[$sub]['total'] . ')' : ''); ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>

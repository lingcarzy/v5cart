<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="sitemap-info">
    <div class="left">
      <ul>
        <?php foreach ($categories as $category_1) { ?>
        <li><a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <?php if ($category_1['children']) { ?>
          <ul>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
              <?php if ($category_2['children']) { ?>
              <ul>
                <?php foreach ($category_2['children'] as $category_3) { ?>
                <li><a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
    <div class="right">
      <ul>
        <li><a href="<?php echo U('product/special'); ?>"><?php echo $text_special; ?></a></li>
        <li><a href="<?php echo U('account/account', '', 'SSL'); ?>"><?php echo $text_account; ?></a>
          <ul>
            <li><a href="<?php echo U('account/edit', '', 'SSL'); ?>"><?php echo $text_edit; ?></a></li>
            <li><a href="<?php echo U('account/password', '', 'SSL'); ?>"><?php echo $text_password; ?></a></li>
            <li><a href="<?php echo U('account/address', '', 'SSL'); ?>"><?php echo $text_address; ?></a></li>
            <li><a href="<?php echo U('account/order', '', 'SSL'); ?>"><?php echo $text_history; ?></a></li>
            <li><a href="<?php echo U('account/download', '', 'SSL'); ?>"><?php echo $text_download; ?></a></li>
          </ul>
        </li>
        <li><a href="<?php echo U('checkout/cart'); ?>"><?php echo $text_cart; ?></a></li>
        <li><a href="<?php echo U('checkout/checkout', '', 'SSL'); ?>"><?php echo $text_checkout; ?></a></li>
        <li><a href="<?php echo  U('product/search'); ?>"><?php echo $text_search; ?></a></li>
        <li><?php echo $text_information; ?>
          <ul>
            <?php foreach ($pages as $page) { ?>
            <li><a href="<?php echo $page['href']; ?>"><?php echo $page['title']; ?></a></li>
            <?php } ?>
            <li><a href="<?php echo U('page/contact'); ?>"><?php echo $text_contact; ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
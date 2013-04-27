<!DOCTYPE html>
<html dir="<?php echo $_['direction']; ?>" lang="<?php echo $_['code']; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="view/javascript/common.js"></script>
<script type="text/javascript" src="view/javascript/jquery/jquery-powerFloat-min.js"></script>
<link type="text/css" href="view/stylesheet/powerFloat.css" rel="stylesheet" />
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm('<?php echo $_['text_confirm']; ?>')) {
                return false;
            }
        }
    });
    	
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
            if (!confirm('<?php echo $_['text_confirm']; ?>')) {
                return false;
            }
        }
    });
});
</script>
</head>
<body>
<div id="container">
    <div id="header">
  <div class="div1">
    <div class="div2"><a href="<?php echo $home; ?>"><img src="view/image/logo.png"></a></div>
    <?php if ($logged) { ?>
    <div class="div3"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
    <?php } ?>
  </div>
  <?php if ($logged) { ?>
  <div id="menu">
    <ul class="left" style="display: none;">
      <li id="dashboard"><a href="<?php echo $home; ?>" class="top"><?php echo $_['text_dashboard']; ?></a></li>
      <li id="catalog"><a class="top"><?php echo $_['text_catalog']; ?></a>
        <ul>
          <li><a href="<?php echo UA('catalog/category'); ?>"><?php echo $_['text_category']; ?></a></li>
          <li><a class="parent"><?php echo $_['text_product'];?></a>
          <ul><li><a href="<?php echo UA('catalog/product'); ?>"><?php echo $_['text_product']; ?></a></li>
          <li><a href="<?php echo UA('catalog/product_group'); ?>"><?php echo $_['text_product_group']; ?></a></li>
          <li><a href="<?php echo UA('catalog/product_tpl'); ?>"><?php echo $_['text_product_template']; ?></a></li>
          <li><a href="<?php echo UA('catalog/specials'); ?>"><?php echo $_['text_special']; ?></a></li>
          </ul></li>
          <li><a class="parent"><?php echo $_['text_attribute']; ?></a>
            <ul>
              <li><a href="<?php echo UA('catalog/attribute'); ?>"><?php echo $_['text_attribute']; ?></a></li>
              <li><a href="<?php echo UA('catalog/attribute_group'); ?>"><?php echo $_['text_attribute_group']; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo UA('catalog/option'); ?>"><?php echo $_['text_option']; ?></a></li>
          <li><a href="<?php echo UA('catalog/manufacturer'); ?>"><?php echo $_['text_manufacturer']; ?></a></li>
          <li><a href="<?php echo UA('catalog/supplier'); ?>"><?php echo $_['text_supplier']; ?></a></li>
          <li><a href="<?php echo UA('catalog/download'); ?>"><?php echo $_['text_download']; ?></a></li>
          <li><a href="<?php echo UA('catalog/review'); ?>"><?php echo $_['text_review']; ?></a></li>
          <li><a href="<?php echo UA('catalog/page'); ?>"><?php echo $_['text_page']; ?></a></li>
        </ul>
      </li>
      <li id="extension"><a class="top"><?php echo $_['text_extension']; ?></a>
        <ul>
          <li><a href="<?php echo UA('extension/module'); ?>"><?php echo $_['text_module']; ?></a></li>
          <li><a href="<?php echo UA('extension/shipping'); ?>"><?php echo $_['text_shipping']; ?></a></li>
          <li><a href="<?php echo UA('extension/payment'); ?>"><?php echo $_['text_payment']; ?></a></li>
          <li><a href="<?php echo UA('extension/total'); ?>"><?php echo $_['text_total']; ?></a></li>
          <li><a href="<?php echo UA('extension/feed'); ?>"><?php echo $_['text_feed']; ?></a></li>
          <li><a href="<?php echo UA('tool/livechat'); ?>"><?php echo $_['text_livechat']; ?></a></li>
        </ul>
      </li>
      <li id="sale"><a class="top"><?php echo $_['text_sale']; ?></a>
        <ul>
          <li><a href="<?php echo UA('sale/order'); ?>"><?php echo $_['text_order']; ?></a></li>
          <li><a href="<?php echo UA('sale/order_basket'); ?>"><?php echo $_['text_order_basket']; ?></a></li>
          <li><a href="<?php echo UA('sale/return'); ?>"><?php echo $_['text_return']; ?></a></li>
          <li><a class="parent"><?php echo $_['text_customer']; ?></a>
            <ul>
              <li><a href="<?php echo UA('sale/customer'); ?>"><?php echo $_['text_customer']; ?></a></li>
              <li><a href="<?php echo UA('sale/customer_group'); ?>"><?php echo $_['text_customer_group']; ?></a></li>
              <li><a href="<?php echo UA('sale/customer_blacklist'); ?>"><?php echo $_['text_customer_blacklist']; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo UA('sale/affiliate'); ?>"><?php echo $_['text_affiliate']; ?></a></li>
          <li><a href="<?php echo UA('sale/coupon'); ?>"><?php echo $_['text_coupon']; ?></a></li>
          <li><a class="parent"><?php echo $_['text_voucher']; ?></a>
            <ul>
              <li><a href="<?php echo UA('sale/voucher'); ?>"><?php echo $_['text_voucher']; ?></a></li>
              <li><a href="<?php echo UA('sale/voucher_theme'); ?>"><?php echo $_['text_voucher_theme']; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo UA('sale/contact'); ?>"><?php echo $_['text_contact']; ?></a></li>
        </ul>
      </li>
      <li id="system"><a class="top"><?php echo $_['text_system']; ?></a>
        <ul>
          <li><a href="<?php echo UA('setting/store'); ?>"><?php echo $_['text_setting']; ?></a></li>
          <li><a href="<?php echo UA('setting/cache'); ?>"><?php echo $_['text_cache']; ?></a></li>       
          <li><a class="parent"><?php echo $_['text_design']; ?></a>
            <ul>
              <li><a href="<?php echo UA('design/layout'); ?>"><?php echo $_['text_layout']; ?></a></li>
              <li><a href="<?php echo UA('design/banner'); ?>"><?php echo $_['text_banner']; ?></a></li>
            </ul>
          </li>       
          <li><a class="parent"><?php echo $_['text_users']; ?></a>
            <ul>
              <li><a href="<?php echo UA('user/user'); ?>"><?php echo $_['text_user']; ?></a></li>
              <li><a href="<?php echo UA('user/user_permission'); ?>"><?php echo $_['text_user_group']; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $_['text_localisation']; ?></a>
            <ul>
              <li><a href="<?php echo UA('localisation/language'); ?>"><?php echo $_['text_language']; ?></a></li>
              <li><a href="<?php echo UA('localisation/currency'); ?>"><?php echo $_['text_currency']; ?></a></li>
              <li><a href="<?php echo UA('localisation/stock_status'); ?>"><?php echo $_['text_stock_status']; ?></a></li>
              <li><a href="<?php echo UA('localisation/order_status'); ?>"><?php echo $_['text_order_status']; ?></a></li>
              <li><a class="parent"><?php echo $_['text_return']; ?></a>
                <ul>
                  <li><a href="<?php echo UA('localisation/return_status'); ?>"><?php echo $_['text_return_status']; ?></a></li>
                  <li><a href="<?php echo UA('localisation/return_action'); ?>"><?php echo $_['text_return_action']; ?></a></li>
                  <li><a href="<?php echo UA('localisation/return_reason'); ?>"><?php echo $_['text_return_reason']; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo UA('localisation/carrier'); ?>"><?php echo $_['text_carrier']; ?></a></li>
              <li><a href="<?php echo UA('localisation/country'); ?>"><?php echo $_['text_country']; ?></a></li>
              <li><a href="<?php echo UA('localisation/zone'); ?>"><?php echo $_['text_zone']; ?></a></li>
              <li><a href="<?php echo UA('localisation/geo_zone'); ?>"><?php echo $_['text_geo_zone']; ?></a></li>
              <li><a class="parent"><?php echo $_['text_tax']; ?></a>
                <ul>
                  <li><a href="<?php echo UA('localisation/tax_class'); ?>"><?php echo $_['text_tax_class']; ?></a></li>
                  <li><a href="<?php echo UA('localisation/tax_rate'); ?>"><?php echo $_['text_tax_rate']; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo UA('localisation/length_class'); ?>"><?php echo $_['text_length_class']; ?></a></li>
              <li><a href="<?php echo UA('localisation/weight_class'); ?>"><?php echo $_['text_weight_class']; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo UA('tool/error_log');; ?>"><?php echo $_['text_error_log']; ?></a></li>
         <li><a class="parent"><?php echo $_['text_database'];?></a>
         <ul>
             <li><a href="<?php echo UA('tool/backup'); ?>"><?php echo $_['text_backup']; ?></a></li>
             <li><a href="<?php echo UA('tool/export'); ?>"><?php echo $_['text_export']; ?></a></li>
          </ul>
         </li>
          <li><a href="<?php echo UA('setting/seo_url'); ?>"><?php echo $_['text_seo_url']; ?></a></li>
          <li><a class="parent"><?php echo $_['text_paypal_express']; ?></a>
            <ul>
            <li><a href="<?php echo UA('sale/paypal_express'); ?>">Sessions</a></li>
            <li><a href="<?php echo UA('sale/paypal_express/payment'); ?>">Payments</a></li>
            <li><a href="<?php echo UA('sale/paypal_express/error'); ?>">Errors</a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li id="reports"><a class="top"><?php echo $_['text_reports']; ?></a>
        <ul>
          <li><a class="parent"><?php echo $_['text_sale']; ?></a>
            <ul>
              <li><a href="<?php echo UA('report/sale_order'); ?>"><?php echo $_['text_report_sale_order']; ?></a></li>
              <li><a href="<?php echo UA('report/sale_tax'); ?>"><?php echo $_['text_report_sale_tax']; ?></a></li>
              <li><a href="<?php echo UA('report/sale_shipping'); ?>"><?php echo $_['text_report_sale_shipping']; ?></a></li>
              <li><a href="<?php echo UA('report/sale_return'); ?>"><?php echo $_['text_report_sale_return']; ?></a></li>
              <li><a href="<?php echo UA('report/sale_coupon'); ?>"><?php echo $_['text_report_sale_coupon']; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $_['text_product']; ?></a>
            <ul>
              <li><a href="<?php echo UA('report/product_viewed'); ?>"><?php echo $_['text_report_product_viewed']; ?></a></li>
              <li><a href="<?php echo UA('report/product_purchased'); ?>"><?php echo $_['text_report_product_purchased']; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $_['text_customer']; ?></a>
            <ul>
              <li><a href="<?php echo UA('report/customer_online'); ?>"><?php echo $_['text_report_customer_online']; ?></a></li>
              <li><a href="<?php echo UA('report/customer_order'); ?>"><?php echo $_['text_report_customer_order']; ?></a></li>
              <li><a href="<?php echo UA('report/customer_reward'); ?>"><?php echo $_['text_report_customer_reward']; ?></a></li>
              <li><a href="<?php echo UA('report/customer_credit'); ?>"><?php echo $_['text_report_customer_credit']; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $_['text_affiliate']; ?></a>
            <ul>
              <li><a href="<?php echo UA('report/affiliate_commission'); ?>"><?php echo $_['text_report_affiliate_commission']; ?></a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li id="help"><a class="top"><?php echo $_['text_help']; ?></a>
        <ul>
          <li><a href="http://www.v5cart.com');"><?php echo $_['text_v5cart']; ?></a></li>
          <li><a href="http://www.v5cart.com/document" target="_blank"><?php echo $_['text_documentation']; ?></a></li>
          <li><a href="http://forum.v5cart.com" target="_blank"><?php echo $_['text_support']; ?></a></li>
          <li><a href="http://www.opencart.com" target="_blank">OpenCart</a></li>
        </ul>
      </li>
    </ul>
    <ul class="right">
      <li><a class="top">Server Time: <?php echo date('m/d/Y H:i');?></a></li>
      <li id="store"><a href="<?php echo HTTP_CATALOG; ?>" target="_blank" class="top"><?php echo $_['text_front']; ?></a>
        <ul>
          <?php foreach ($stores as $stores) { ?>
          <li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <li id="store"><a class="top" href="<?php echo UA('common/logout'); ?>"><?php echo $_['text_logout']; ?></a></li>
    </ul>
    <script type="text/javascript"><!--
$(document).ready(function() {
    $('#menu > ul').superfish({
        hoverClass   : 'sfHover',
        pathClass    : 'overideThisToUse',
        delay        : 0,
        animation    : {height: 'show'},
        speed        : 'normal',
        autoArrows   : false,
        dropShadows  : false, 
        disableHI    : false, /* set to true to disable hoverIntent detection */
        onInit       : function(){},
        onBeforeShow : function(){},
        onShow       : function(){},
        onHide       : function(){}
    });
    
    $('#menu > ul').css('display', 'block');
});
 
function getURLVar(urlVarName) {
    var urlHalves = String(document.location).toLowerCase().split('?');
    var urlVarValue = '';
    
    if (urlHalves[1]) {
        var urlVars = urlHalves[1].split('&');

        for (var i = 0; i <= (urlVars.length); i++) {
            if (urlVars[i]) {
                var urlVarPair = urlVars[i].split('=');
                
                if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
                    urlVarValue = urlVarPair[1];
                }
            }
        }
    }
    
    return urlVarValue;
} 

$(document).ready(function() {
    route = getURLVar('route');
    
    if (!route) {
        $('#dashboard').addClass('selected');
    } else {
        part = route.split('/');
        
        url = part[0];
        
        if (part[1]) {
            url += '/' + part[1];
        }
        
        $('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
    }
});
//--></script> 
  </div>
  <?php } ?>
</div>
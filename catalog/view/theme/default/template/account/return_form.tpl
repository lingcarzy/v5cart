<?php echo $header; ?>
<?php if (isset($error_warning)) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php echo $text_description; ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <h2><?php echo $text_order; ?></h2>
    <div class="content">
      <div class="left"><span class="required">*</span> <?php echo $entry_firstname; ?><br />
        <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" data-rule-required="true" data-rule-rangelength="1,32" />
        <br />
        <?php echo form_error('firstname'); ?>
        <br />
        <span class="required">*</span> <?php echo $entry_lastname; ?><br />
        <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" data-rule-required="true" data-rule-rangelength="1,32" />
        <br />
        <?php echo form_error('lastname'); ?>
        <br />
        <span class="required">*</span> <?php echo $entry_email; ?><br />
        <input type="text" name="email" value="<?php echo $email; ?>" class="large-field" data-rule-required="true" data-rule-rangelength="5,96" data-rule-email="true" />
        <br />
        <?php echo form_error('email'); ?>
        <br />
        <span class="required">*</span> <?php echo $entry_telephone; ?><br />
        <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field" data-rule-required="true" data-rule-rangelength="3,32" />
        <br />
        <?php echo form_error('telephone'); ?>
        <br />
      </div>
      <div class="right"><span class="required">*</span> <?php echo $entry_order_id; ?><br />
        <input type="text" name="order_id" value="<?php echo $order_id; ?>" class="large-field" data-rule-required="true" />
        <br />
        <?php echo form_error('order_id'); ?>
        <br />
        <?php echo $entry_date_ordered; ?><br />
        <input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="large-field date" />
        <br />
      </div>
    </div>
    <h2><?php echo $text_product; ?></h2>
    <div id="return-product">
      <div class="content">
        <div class="return-product">
          <div class="return-name"><span class="required">*</span> <b><?php echo $entry_product; ?></b><br />
            <input type="text" name="product" value="<?php echo $product; ?>" data-rule-required="true" data-rule-rangelength="1,255" />
            <br />
            <?php echo form_error('product'); ?>
          </div>
          <div class="return-model"><span class="required">*</span> <b><?php echo $entry_model; ?></b><br />
            <input type="text" name="model" value="<?php echo $model; ?>" data-rule-required="true" data-rule-rangelength="1,64"/>
            <br />
            <?php echo form_error('model'); ?>
          </div>
          <div class="return-quantity"><b><?php echo $entry_quantity; ?></b><br />
            <input type="text" name="quantity" value="<?php echo $quantity; ?>" />
          </div>
        </div>
        <div class="return-detail">
          <div class="return-reason"><span class="required">*</span> <b><?php echo $entry_reason; ?></b><br />
            <table>
              <?php foreach ($return_reasons as $return_reason) { ?>
              <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
              <tr>
                <td width="1"><input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" checked="checked" /></td>
                <td><label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label></td>
              </tr>
              <?php } else { ?>
              <tr>
                <td width="1"><input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" /></td>
                <td><label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label></td>
              </tr>
              <?php  } ?>
              <?php  } ?>
            </table>
            <?php echo form_error('return_reason_id'); ?>
          </div>
          <div class="return-opened"><b><?php echo $entry_opened; ?></b><br />
            <?php if ($opened) { ?>
            <input type="radio" name="opened" value="1" id="opened" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="opened" value="1" id="opened" />
            <?php } ?>
            <label for="opened"><?php echo $text_yes; ?></label>
            <?php if (!$opened) { ?>
            <input type="radio" name="opened" value="0" id="unopened" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="opened" value="0" id="unopened" />
            <?php } ?>
            <label for="unopened"><?php echo $text_no; ?></label>
            <br />
            <br />
            <?php echo $entry_fault_detail; ?><br />
            <textarea name="comment" cols="150" rows="6"><?php echo $comment; ?></textarea>
          </div>
          <div class="return-captcha"><b><?php echo $entry_captcha; ?></b><br />
            <input type="text" name="captcha" value="<?php echo $captcha; ?>" data-rule-required="true" />
            <br />
            <img src="index.php?route=account/return/captcha" alt="" />
             <?php echo form_error('captcha'); ?>
          </div>
        </div>
      </div>
    </div>
  <?php if ($text_agree) { ?>
	  <div class="buttons">
		  <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
		  <div class="right"><?php echo $text_agree; ?>
			  <?php if ($agree) { ?>
				  <input type="checkbox" name="agree" value="1" checked="checked" />
				  <?php } else { ?>
				  <input type="checkbox" name="agree" value="1" />
			  <?php } ?>
			  <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
		  </div>
	  </div>
	  <?php } else { ?>
	  <div class="buttons">
		  <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
		  <div class="right">
			  <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
		  </div>
	  </div>
  <?php } ?>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>
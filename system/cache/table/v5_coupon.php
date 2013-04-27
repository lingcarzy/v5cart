<?php return array (
  'coupon_id' => 'int(11)',
  'name' => 'varchar(128)',
  'code' => 'varchar(10)',
  'type' => 'char(1)',
  'discount' => 'decimal(15,4)',
  'logged' => 'tinyint(1)',
  'shipping' => 'tinyint(1)',
  'total' => 'decimal(15,4)',
  'date_start' => 'date',
  'date_end' => 'date',
  'uses_total' => 'int(11)',
  'uses_customer' => 'varchar(11)',
  'status' => 'tinyint(1)',
  'date_added' => 'datetime',
); ?>
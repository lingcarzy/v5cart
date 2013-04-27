<?php

$_['config_seo_rules']= array(
	'category' => array(
		0 => array('rule' => '{seo_url}-{cate_id}/', 'example' => 'Example: pc-56/'),
		1 => array('rule' => '{parent_cate_dir}/{seo_url}-{cate_id}/', 'example' => 'Example: desktops/pc-56/'),
	),
	'product' => array(
		0 => array('rule' => '{seo_url}-{product_id}.html', 'example' => 'Example: hp-lp3065-47.html'),
		1 => array('rule' => '{cate_dir}/{seo_url}-{product_id}.html', 'example' => 'Example: pc/hp-lp3065-47.html'),
		2 => array('rule' => '{parent_cate_dir}/{cate_dir}/{seo_url}-{product_id}.html', 'example' => 'Example: desktops/pc/hp-lp3065-47.html'),
	),
);
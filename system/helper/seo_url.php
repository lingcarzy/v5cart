<?php

function seo_url_rewrite($route, $args) {
	global $CATEGORIES;
	static $find = array('{parent_cate_dir}', '{cate_dir}', '{seo_url}', '{product_id}', '{cate_id}');
	$category_seo_rule = C('category_seo_rule');
	$product_seo_rule = C('product_seo_rule');

	if ($route == 'product/product' || $route == 'product/category') {
		parse_str($args, $url_data);
		$seo_url = $parent_cate_dir = $cate_dir = '';
		$cate_id = $product_id = 0;
		if (isset($url_data['cate_id'])) {
			$cate_id = $url_data['cate_id'];
			unset($url_data['cate_id']);
		}
		if(isset($CATEGORIES[$cate_id])) {
			$seo_url = $cate_dir = $CATEGORIES[$cate_id]['url'];
			$paths = explode(',', $CATEGORIES[$cate_id]['path']);
			array_pop($paths);
			foreach($paths as $path) {
				$parent_cate_dir .= $CATEGORIES[$path]['url'] ? $CATEGORIES[$path]['url'] . '/' : '';
			}
			$parent_cate_dir = trim($parent_cate_dir, '/');
		}
		if ($route == 'product/product') {
			if (isset($url_data['seo_url'])) {
				$seo_url = $url_data['seo_url'];
				unset($url_data['seo_url']);
			}
			else $seo_url = '';
			$product_id = $url_data['product_id'];
			unset($url_data['product_id']);
			$replace = array(
				$parent_cate_dir,
				$cate_dir,
				$seo_url ? $seo_url : 'product',
				$product_id,
				'',
			);
			$url = str_replace($find, $replace, $product_seo_rule);
			$url = str_replace('//', '/', $url);
		}
		else {
			$replace = array(
				$parent_cate_dir,
				'',
				$seo_url ? $seo_url : 'category',
				'',
				$cate_id
			);
			$url = str_replace($find, $replace, $category_seo_rule);
		}
		if (!empty($url_data)) {
			$url .= "?" . http_build_query($url_data);
		}
		return ltrim($url, '/');
	}
	else {
		$url = $route;
		if ($args) {
			$url .= '?' . trim($args, '&');
		}
		return $url;
	}
}
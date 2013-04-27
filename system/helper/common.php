<?php
//cache functions
function cache_read($file, $dir = '', $mode = '') {
	$file = _get_cache_file($file, $dir);
	if(!is_file($file)) return NULL;
	return $mode ? file_get_contents($file) : include $file;
}

function cache_write($file, $string, $dir = '') {
	if(is_array($string)) {
		$string = "<?php return ".var_export($string, true)."; ?>";
		//$string =  str_replace(array(chr(13), chr(10), "\n", "\r", "\t", '  '),array('', '', '', '', '', ''), $string);
	}
	$file = _get_cache_file($file, $dir);
	return file_put_contents($file, $string);
}

function cache_delete($file, $dir = '') {
	$file = _get_cache_file($file, $dir);
	return unlink($file);
}

function _get_cache_file($file, $dir) {
	return ($dir ? DIR_CACHE . $dir.'/'.$file : DIR_CACHE.$file);
}

//shortcuts
function C($key, $value = null) {
	if ($value) $GLOBALS['config']->set($key, $value);
	return $GLOBALS['config']->get($key);
}

function L($key) {
	return $GLOBALS['language']->get($key);
}

function P($key, $default = null) {
	global $request;
	if (isset($request->post[$key])) {
		return v5_trim($request->post[$key]);
	}
	return $default;
}

function G($key, $default = null) {
	global $request;
	if (isset($request->get[$key])) {
		return v5_trim($request->get[$key]);
	}
	return $default;
}

function M($model, $name = null) {
	$GLOBALS['loader']->model($model, $name);
}

function ES($str) {
	return $GLOBALS['db']->escape($str);
}

//trim
function v5_trim($val) {
	if (is_array($val)) {
		$result = array();
		foreach($val as $k => $v) {
			$result[$k] = v5_trim($v);
		}
		return $result;
	}
	else return trim($val);
}

function range_length($str, $min, $max) {
	$len = utf8_strlen($str);
	return ($len < $min || $len > $max) ? FALSE : TRUE;
}

//html form function
function form_select_option($arr,  $default = NULL,  $use_key = FALSE, $key = NULL, $val = NULL) {
	$html = '';
	
	foreach ($arr as $k => $v) {
		if (is_array($v)) {
			$_k = $use_key ? $k : $v[$key];
			$_v = $v[$val];
		}
		else {
			$_k = $use_key ? $k : $v;
			$_v = $v;
		}
		$selected = !is_null($default) ? (($default == $_k) ? " selected='selected'" : '') : '';
		$html .= "<option value='$_k'" . $selected . ">$_v</option>";
	}
	return $html;
}

function form_radio($arr, $name, $default = NULL) {
	$html = '';
	foreach ($arr as $k => $v) {
		$checked = !is_null($default) ? (($default == $k) ? ' checked="checked"' : '') : '';
		$html .= '<input type="radio" name="'.$name.'" value="'.$k.'"' . $checked . ' />' . $v . "\n";
	}
	return $html;
}

function form_error($key) {
	global $registry;
	if (!$registry->has('form_validation')) {
		return '';
	}
	else return $registry->get('form_validation')->error($key);
}
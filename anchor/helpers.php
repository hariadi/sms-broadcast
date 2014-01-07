<?php

function __($line) {
	$args = array_slice(func_get_args(), 1);

	return Language::line($line, null, $args);
}

function is_admin() {
	return strpos(Uri::current(), 'admin') === 0;
}

function is_installed() {
	return Config::get('db') !== null or Config::get('database') !== null;
}

function slug($str, $separator = '-') {
	$str = normalize($str);

	// replace non letter or digits by separator
	$str = preg_replace('#^[^A-z0-9]+$#', $separator, $str);

	return trim(strtolower($str), $separator);
}

function parse($str, $markdown = true) {
	// process tags
	$pattern = '/[\{\{]{1}([a-z]+)[\}\}]{1}/i';

	if(preg_match_all($pattern, $str, $matches)) {
		list($search, $replace) = $matches;

		foreach($replace as $index => $key) {
			$replace[$index] = Config::meta($key);
		}

		$str = str_replace($search, $replace, $str);
	}

	$str = html_entity_decode($str, ENT_NOQUOTES, System\Config::app('encoding'));

	//  Parse Markdown as well?
	if($markdown === true) {
		$md = new Markdown;
		$str = $md->transform($str);
	}

	return $str;
}

function readable_size($size) {
	$unit = array('b','kb','mb','gb','tb','pb');

	return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function normalize_number($numbers) {
	if (is_array($numbers)) {

		$searchReplaceArray = array(
		  ' ' => '', 
		  '+' => ''
		);

		$result = str_replace(
		  array_keys($searchReplaceArray), 
		  array_values($searchReplaceArray), 
		  $numbers
		);
		
	}
	return $result;
}

function search_for($array, $key, $val) {
    foreach ($array as $item)
        if (isset($item[$key]) && $item[$key] == $val)
            return true;
    return false;
}

function range_number($array, $start = 1, $end = 31) {
		if (empty($array)) {
			$numbers = array();
	  	for ($number=$start; $number < ($end+1); $number++) { 
				$numbers[] = $number;
			}
			$implode = $numbers;
		} else {
			$implode = $array;
		}
    return implode(',', $implode);
}




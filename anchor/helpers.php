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

function schedule_name($item, $type) {

	$items = explode(',', $item);

	$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');

	$weeks = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');


	$filter = ($type == 'monthly') ? $months : $weeks;

	$schedule = array_intersect_key($filter, array_flip($items));

	return implode(', ', $schedule);
}

function abbreviation($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
		return (($number %100) >= 11 && ($number%100) <= 13) ? $number. 'th' : $number. $ends[$number % 10];
}

function money($number = 0) {
	return number_format((float) abs($number), 2, '.', '');
}

function ExportToExcel($tittles, $filename, $data, $excel_name = ' ')
 {
 	$excel_name = $excel_name ? $excel_name . ' Report' : 'Report';

	require PATH . 'vendor/PHPExcel/PHPExcel' . EXT;

	$excel = new PHPExcel();

	// Set properties
	$excel->getProperties()->setCreator("Transrec")
						 ->setLastModifiedBy("Transrec")
						 ->setTitle($excel_name)
						 ->setSubject($excel_name)
						 ->setDescription($excel_name);


	// Add some data
	$excel->setActiveSheetIndex(0);

	$letters = range('A','Z');
	$cell_name="";
	foreach($tittles as $count => $tittle)
	{
		$cell_name = $letters[$count]."1";
		$value = $tittle;
		$excel->getActiveSheet()->SetCellValue($cell_name, $value);
	}
	$excel->getActiveSheet()->getStyle("$cell_name:" . $letters[$count] . (String) $count)->getFont()->setBold(true);

	// Save Excel 2007 file
	$objWriter = new PHPExcel_Writer_Excel2007($excel);
	//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
	$objWriter->save($filename.".xlsx");
 }





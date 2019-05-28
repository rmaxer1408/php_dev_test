<?php
$memory_start = memory_get_usage();
require_once 'index.html';
function Search($fileName, $keyValue ){
	function binSearch($arr, $key, $start = 0, $finish = null) {
		if ($finish === null){
			$finish = count($arr)-1;
		}		
		if ($start > $finish){
			return print 'undef';		
		}		
		$middle = (int)(($start+$finish)/2);
		$i = strnatcmp($arr[$middle][0], $key);
		if ($i !== 0) {
			if ($i < 0) {
				$start = $middle + 1;
			}
			else {
				$finish = $middle - 1;
			}
			return binSearch($arr, $key, $start, $finish);
		}
		return print($arr[$middle][1]);		
	}
	$handle = fopen($fileName, 'r');
	$string = '';
	while ( !feof($handle)) {
		$string = fread($handle, 4000);
		$arrString = preg_split('/\\\x0A/', $string);
		array_pop($arrString);		 
		foreach ($arrString as $value) {			
			$arr[] = preg_split('/\\\t/', $value);
		}
	}
	binSearch($arr, $keyValue);
	fclose($handle);
}
echo 'Значение ключа: ';
Search(dirname(__FILE__).'\file.txt', $_GET['name']);
echo '<br>'.'Используемая память: '.(memory_get_usage() - $memory_start).' байт';
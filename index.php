<?php
/* memory in bytes in current moment */
$memory_start = memory_get_usage(); 

$startTime = microtime(true);
// include input form
require_once 'index.html';
# fileName - name of file.txt
function Search($fileName, $keyValue ){
	
	function binSearch($arr, $key, $start = 0, $finish = null) { 
		
		if ($finish === null){	

			$finish = count($arr)-1; // last index of array	
		}		
		
		if ($start > $finish){
			
			return 'undef';	// return if undefined key
		}		
		
		$middle = (int)(($start + $finish) / 2); // average index of array

		$i = strnatcmp($arr[$middle][0], $key); // string sorting 
		# recursive binary search
		if ($i !== 0) {

			if ($i < 0) {

				$start = $middle + 1;
			}
			else {

				$finish = $middle - 1;
			}

			return binSearch($arr, $key, $start, $finish); // recursive function
		}

		return ($arr[$middle][1]);	
	}
	
	$handle = fopen($fileName, 'rb'); // open file.txt for read 
	
	$string = '';	
	
	while ( !feof($handle)) { // until not the end of file
		
		$string = stream_get_line($handle, 4000); // read every 4000 bytes
		
		$arrString = preg_split('/\\\x0A/', $string); // split into arr by \xA0
		
		array_pop($arrString); // delete last element from arr
		
		foreach ($arrString as $value) {			
			
			$arr[] = preg_split('/\\\t/', $value);	// split every string into arr by \t			
		}

		$arrBin[] = (binSearch($arr, $keyValue)); // search key in array
	}
	
	echo "<pre>";
	print_r(end($arrBin));
	echo "</pre>";	

	fclose($handle); // close file
}

echo 'Значение ключа: '; // output value

Search(dirname(__FILE__).'\file.txt', $_GET['name']); // input file.txt and key in index.html

echo 'Скрипт был выполнен за ' . (microtime(true) - $startTime) . ' sec';

echo '<br>'.'Используемая память: '.(memory_get_usage() - $memory_start).' b';

echo '<br>'.'Размер файла: '.(filesize(dirname(__FILE__).'\file.txt')).' b';
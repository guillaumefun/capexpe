<?php
ini_set('display_errors', 1);
                          ini_set('display_startup_errors', 1);
                          error_reporting(E_ALL);

$file = 'ListExportFile2011.txt';
$all = 'allblog2011.txt';
$f = file_get_contents($file);

$blog2keep = explode("\n", $f);
$y = count($blog2keep);

$f2 = file_get_contents($all);

$allblogs = explode("\n", $f2);
$x = count($allblogs);

for ($i=0; $i < $x ; $i++) { 

	$blog = explode(';', $allblogs[$i]);

	for($j=0 ; $j < $y ; $j++ ){
		$blog2 = explode(';', $blog2keep[$j]);
		if ($blog[4] == $blog2[0]) {
			echo $allblogs[$i] . '<br>';
		}
	}
		
}



?>
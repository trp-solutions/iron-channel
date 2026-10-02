<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function sample_start() : void {
	header('Content-Type: text/plain; charset=utf-8');
}

function sample_baseurl() : string {
	if(($pos = mb_strpos($_SERVER['SCRIPT_NAME'],'/IronChannel'))!==false) {
		$baseurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].mb_substr($_SERVER['SCRIPT_NAME'],0,$pos+12);
	}
	else {
		sample_header('Sample setting fail');
		echo 'baseurl not detected - please define in client/function.php - exiting'.PHP_EOL;
		exit;
		$baseurl = 'https://example.com';
	}
	sample_header('BaseURL');
	echo $baseurl.PHP_EOL;
	return $baseurl;
}

function sample_header(string $str) : void {
	echo PHP_EOL.'========> '.str_pad($str,40,pad_type: STR_PAD_BOTH).' <========'.PHP_EOL;
}

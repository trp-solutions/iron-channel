<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function dump() : void {
	$request_id = date('Ymd-his').'-'.substr(str_shuffle(str_repeat($x='0123456789ABCDEF', (int) ceil(10/strlen($x)) )),1,4);
	syslog(LOG_INFO,'DUMP: '.$request_id);

	$info = [];
	syslog(LOG_INFO,'SERVER: '.var_export($_SERVER,true));
	$info['SERVER'] = $_SERVER;

	syslog(LOG_INFO,'GET: '.var_export($_GET,true));
	$info['GET'] = $_GET;

	syslog(LOG_INFO,'POST: '.var_export($_POST,true));
	$info['POST'] = $_POST;

	syslog(LOG_INFO,'FILES: '.var_export($_FILES,true));
	$info['FILES'] = $_FILES;

	syslog(LOG_INFO,'COOKIE: '.var_export($_COOKIE,true));
	$info['COOKIE'] = $_COOKIE;

	$header = getallheaders();
	syslog(LOG_INFO,'HEADER: '.var_export($header,true));
	$info['HEADER'] = $header;

	if(is_writable(__DIR__.'/dump/')) {
		$info = json_encode($info, JSON_PRETTY_PRINT);
		file_put_contents(__DIR__.'/dump/'.''.$request_id.'-info.json',$info);

		$input = file_get_contents('php://input');
		file_put_contents(__DIR__.'/dump/'.''.$request_id.'-body.bin',$input);
	}
}

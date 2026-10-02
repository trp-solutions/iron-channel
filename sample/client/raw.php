<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
require_once __DIR__.'/../../lib/autoload.php';
require_once __DIR__.'/function.php';

sample_start();

$baseurl = sample_baseurl();
\TRP\IronChannel\Client::baseurl($baseurl);
\TRP\IronChannel\Client::log(new \TRP\IronChannel\Syslog());

$raw = new \TRP\IronChannel\RawString('RAW_FILE','text/plain');

$client = new \TRP\IronChannel\Client('/sample/server/picture',$raw);
$client->query('filename','test.jpg');

try {
	$saved = $client->execute();
}
catch(\Exception $e) {
	sample_header('Exception');
	echo $e->getMessage().PHP_EOL;
	echo $client->response().PHP_EOL;
	exit;
}

sample_header('Success');
echo $saved;

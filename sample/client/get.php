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
\TRP\IronChannel\Client::useragent('PHPTimeClient/1.0');
\TRP\IronChannel\Client::log(new \TRP\IronChannel\Syslog());

if(false) {
	sample_header('Setting Timeout');
	\TRP\IronChannel\Client::setopt(CURLOPT_TIMEOUT,1);
}

if(false) {
	sample_header('Allow unsure connection');
	\TRP\IronChannel\Client::setopt(CURLOPT_SSL_VERIFYPEER,false);
	\TRP\IronChannel\Client::setopt(CURLOPT_SSL_VERIFYHOST,false);
}

$client = new \TRP\IronChannel\Client('/sample/server/time');
$client->query('timezone','America/Jamaica');

if(true) {
	$client->auth(new \TRP\IronChannel\BasicAuth('john','Pa55w0rd'));
}

try {
	$time = $client->execute();
}
catch(\Exception $e) {
	sample_header('Exception');
	echo $e->getMessage().PHP_EOL;
	echo $client->response().PHP_EOL;
	exit;
}

sample_header('Success');
echo $time;

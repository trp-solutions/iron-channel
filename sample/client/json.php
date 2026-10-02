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

$data = new \TRP\IronChannel\JSON(['a' => 10,'b' => 20,'timestamp' => time()]);
$client = new \TRP\IronChannel\Client('/sample/server/calculate/add',$data);

if(true) {
	sample_header('JWT Secret suggestion');
	$secret = \TRP\IronChannel\JWTGenerate::generate_secret(48);
	echo 'Secret: '.$secret.PHP_EOL;
	echo 'Length: '.strlen($secret).PHP_EOL;
}

if(true) {
	sample_header('JWT');
	$secret = 'hasta-la-vista-human';
	$jwt = new \TRP\IronChannel\JWTGenerate($secret);
	$jwt->payload('app_id','skynet');
	$client->auth($jwt);
}

try {
	$return = $client->execute();
}
catch(\Exception $e) {
	sample_header('Exception');
	echo $e->getMessage().PHP_EOL;
	echo $client->response().PHP_EOL;
	exit;
}

sample_header('Success');
var_dump($return);

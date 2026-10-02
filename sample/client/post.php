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

$post = new \TRP\IronChannel\Post();
$post->value('eventname','Holiday One');
$post->file('file',__DIR__.'/test.bin','text/plain','file-'.time().'.txt');
$post->filestring('file2','LineOne','string-'.time().'.txt','text/plain');

$client = new \TRP\IronChannel\Client('/sample/server/filedrop',$post);

if(true) {
	$client->auth(new \TRP\IronChannel\Bearer('_TopSecret!*'));
	$client->header('X-Tenant','FamilyA');
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
echo $return.PHP_EOL;

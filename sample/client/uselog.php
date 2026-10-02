<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
require_once __DIR__.'/../../lib/autoload.php';
require_once __DIR__.'/function.php';

class SampleLog extends \TRP\IronChannel\Syslog {
	protected array $data = [];
	public function data(string $key, mixed $value) : void {
		$this->data[$key] = $value;
	}

	public function log(int $priority, string $message) : void {
		parent::log($priority,$message);
		echo "--- Log --- ".$message.PHP_EOL;
	}

	public function finally(\TRP\IronChannel\Result $result) : void {
		parent::finally($result);
		$success = match ($result) {
			 \TRP\IronChannel\Result::Success => 'yes',
			 \TRP\IronChannel\Result::Fail => 'no',
		};

		$metrics = '';
		foreach($this->metrics as $key => $value) {
			$metrics .= $key.'='.$value.'|';
		}

		$database = $this->data['database'];
		$operation = $this->data['operation'];
		$sql = "INSERT INTO `$database`.`event` ";
		$sql .= "(`time`,`operation`,`success`,`metrics`) ".PHP_EOL;
		$sql .= "VALUES (NOW(),'$operation','$success','$metrics')";
		sample_header('SQL Example - Warning: no string escaping');
		echo $sql.PHP_EOL;
	}
}

sample_start();

$baseurl = sample_baseurl();
\TRP\IronChannel\Client::baseurl($baseurl);

$logger = new SampleLog('IronChannel|SampleClient::');
$logger->data('database','test');

\TRP\IronChannel\Client::log($logger);

try {
	sample_header('Correct endpoint');
	$logger->data('operation','message');
	$request = new \TRP\IronChannel\JSON(['message' => 'Hi']);
	$return = ($client = new \TRP\IronChannel\Client('/sample/server/echo',$request))->execute();
}
catch(\Exception $e) {
	sample_header('Exception handler');
	echo $e->getMessage().PHP_EOL;

	sample_header('Get raw response body');
	echo $client->response().PHP_EOL;
	exit;
}

sample_header('Response');
var_dump($return);

try {
	sample_header('Attempt with WRONG endpoint');
	$logger->data('operation','message');
	$request = new \TRP\IronChannel\JSON(['message' => 'Help']);
	$return = ($client = new \TRP\IronChannel\Client('/sample/WRONG/echo',$request))->execute();
}
catch(\Exception $e) {
	sample_header('Exception handler');
	echo $e->getMessage().PHP_EOL;

	sample_header('Get raw response body');
	echo $client->response().PHP_EOL;
}

sample_header('Done');

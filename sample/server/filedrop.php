<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function server_filedrop() {
	try {
		if(true) {
			$auth = \TRP\IronChannel\Bearer::get();
			$auth->validate('_TopSecret!*');
			\TRP\IronChannel\Server::confirm($auth);
		}
		elseif(false) {
			$callback = function($header) {var_dump($header);return '_TopSecret!*';};
			$auth = \TRP\IronChannel\Bearer::process($callback);
			\TRP\IronChannel\Server::confirm($auth);
		}

		echo '<xml>'.PHP_EOL;
		$eventname = \TRP\IronChannel\Post::read('eventname');
		echo ' <eventname>'.htmlspecialchars($eventname).'</eventname>'.PHP_EOL;
		$file = \TRP\IronChannel\Files::read('file');
		echo ' <file>'.htmlspecialchars($file['name']).'</file>'.PHP_EOL;
		$file2 = \TRP\IronChannel\Files::read('file2');
		echo ' <file2>'.htmlspecialchars($file2['name']).'</file2>'.PHP_EOL;
		echo '</xml>'.PHP_EOL;
	}
	catch(\Exception $e) {
		http_response_code($e->getCode());
		echo $e->getMessage();
		exit;
	}
}

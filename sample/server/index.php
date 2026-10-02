<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
require_once __DIR__.'/../../lib/autoload.php';

if(true) {
	require_once __DIR__.'/dump.php';
	dump();
}

$path = \TRP\IronChannel\Server::path();
switch($path[0]) {
	case 'calculate':
		require_once __DIR__.'/calculate.php';
		server_calculate($path[1] ?? null);
		break;
	case 'filedrop':
		require_once __DIR__.'/filedrop.php';
		server_filedrop();
		break;
	case 'time':
		require_once __DIR__.'/time.php';
		server_time();
		break;
	case 'echo':
		require_once __DIR__.'/echo.php';
		server_echo();
		break;
	case 'picture':
		require_once __DIR__.'/picture.php';
		server_picture();
		break;
	default:
		echo 'OK';
		if($path !== ['']) {
			echo ' ['.implode('/',$path).']';
		}
		break;
}

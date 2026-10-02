<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);

function server_echo() {
	try {
		\TRP\IronChannel\Server::confirm(new \TRP\IronChannel\NoAuth());
		$json = \TRP\IronChannel\JSON::read();
		$reply = $json->message ?? 'Empty';
		$reply .= ' - Back';
		$reply = strrev($reply);
		\TRP\IronChannel\JSON::output($reply);
	}
	catch(\Exception $e) {
		\TRP\IronChannel\JSON::error($e->getMessage(),$e->getCode());
	}
}

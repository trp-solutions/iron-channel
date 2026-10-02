<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

trait BearerTrait {
		private static function get_authorization($headers) : string {
		if(empty($headers['Authorization'])) {
			throw new \Exception('Bearer missing Authorization header',401);
		}
		if(preg_match('/^Bearer\s+(\S+)$/i', $headers['Authorization'], $matches)!==1) {
			throw new \Exception('Bearer missing token',401);
		}
		return $matches[1];
	}
}

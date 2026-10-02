<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class Server {
	private static bool $confirm = false;

	public static function confirm(Auth $auth) : void {
		if($auth->authenticated()===true) {
			self::$confirm = true;
		}
	}
	public static function allowed() : true {
		if(!self::$confirm) {
			throw new \Exception('Server unconfirmed authentication',400);
		}
		return true;
	}
	public static function path() : array {
		return explode('/',trim($_GET['path'] ?? '', '/'));
	}
}

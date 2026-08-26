<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class Get extends CurlOpt implements Data, DataHash {
	public static function read(mixed $name = null) : mixed {
		Server::allowed();
		return $_GET[$name] ?? null;
	}
	public function curl_hash(string $algo, bool $hash_empty_body = true) : ?string {
		return $hash_empty_body ? hash($algo, '') : null;
	}
}

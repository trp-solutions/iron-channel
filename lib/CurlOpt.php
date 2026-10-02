<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class CurlOpt {
	public function curl_header() : array {
		return [];
	}
	public function curl_setopt(\CurlHandle $ch) : void {}
}

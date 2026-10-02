<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class Files implements Data {
	public static function read(mixed $name = null) : mixed {
		Server::allowed();
		return $_FILES[$name] ?? null;
	}
}

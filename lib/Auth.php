<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

interface Auth {
	public static function get() : Auth;
	public static function process(callable $callback) : Auth;
	public function authenticated() : bool;
}

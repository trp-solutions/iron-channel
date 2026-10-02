<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

final class NoAuth implements Auth {
	public static function get() : NoAuth {
		return new self();
	}
	public static function process(callable $callback) : NoAuth {
		return new self();
	}
	public function authenticated() : bool {
		return true;
	}
}

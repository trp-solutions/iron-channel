<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

enum Result: int {
	case Success = 0;
	case Fail = 1;
}

interface Log {
	public function log(int $priority, string $message) : void;
	public function metrics(string $key, mixed $value) : void;
	public function finally(Result $result) : void;
}

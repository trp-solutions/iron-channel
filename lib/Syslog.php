<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class Syslog implements Log {
	private $prefix;
	protected array $metrics = [];

	function __construct(string $prefix = 'IronChannel|') {
		$this->prefix = $prefix;
	}

	public function log(int $priority, string $message) : void {
		syslog($priority,$this->prefix.$message);
	}

	public function metrics(string $key, mixed $value) : void {
		$this->metrics[$key] = $value;
	}

	public function finally(Result $result) : void {
		switch($result) {
			case Result::Success:
				$this->log(\LOG_INFO,'success ['.$this->metrics['time'].']');
				return;
			case Result::Fail:
				return;
		}
	}
}

<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

interface DataHash {
	public function curl_hash(string $algo, bool $hash_empty_body = true) : ?string;
}
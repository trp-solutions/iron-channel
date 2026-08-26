<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class RawString extends CurlOpt implements Data, DataHash {
	private string $data;
	private string $type;

	function __construct(string $data,string $type = 'application/octet-stream') {
		$this->data = $data;
		$this->type = $type;
	}

	public static function read(mixed $name = null) : mixed {
		Server::allowed();
		return file_get_contents('php://input');
	}
	public static function save($path) : void {
		$input = fopen('php://input', 'rb');
		$output = fopen($path, 'wb');
		stream_copy_to_stream($input, $output);
		fclose($input);
		fclose($output);
	}

	public function curl_header() : array {
		return ['Content-Type: '.$this->type];
	}
	public function curl_setopt(\CurlHandle $ch) : void {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
	}
	public function curl_hash(string $algo, bool $hash_empty_body = true) : ?string {
		return empty($this->data) && !$hash_empty_body ? null : hash($algo, $this->data);
	}
}

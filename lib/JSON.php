<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class JSON extends CurlOpt implements Data, DataHash {
	private mixed $data = null;
	private string $method = 'GET';

	function __construct(string|array|object|null $data = null,?string $method = null) {
		if($data) {
			$this->method = 'POST';
			$this->data = $data;
		}
		if($method) {
			$this->method = $method;
		}
	}

	public static function read(mixed $name = null) : mixed {
		Server::allowed();
		$input = file_get_contents('php://input');
		if(empty($input)) {
			throw new \Exception('EmptyInput',400);
		}
		return self::decode($input);
	}

	public static function decode(string $json) : string|array|object|null {
		return json_decode($json, flags: JSON_THROW_ON_ERROR);
	}

	public static function output($value,int $code = 200) : never {
		if(!headers_sent()) {
			http_response_code($code);
			header('Content-Type: application/json');
		}
		echo json_encode($value);
		exit;
	}
	public static function error(string $string,int $code = 400,?array $extra = null) : never {
		$error = ['code' => $code,'message' => $string];
		if($extra) $error += $extra;
		self::output($error,$code);
	}

	public function curl_header() : array {
		return ['Content-Type: application/json'];
	}
	public function curl_setopt(\CurlHandle $ch) : void {
		switch($this->method) {
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				break;
			case 'GET':
				break;
			default:
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
				break;
		}
		if($this->data !== null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
		}
	}
	public function curl_hash(string $algo, bool $hash_empty_body = true) : ?string {
		if($this->data !== null){
			$body = json_encode($this->data);
		} elseif($hash_empty_body){
			$body = '';
		} else {
			return null;
		}
		return hash($algo, $body);
	}
}

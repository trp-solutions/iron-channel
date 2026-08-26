<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

final class JWTVerify implements Auth {
	use BearerTrait;

	private bool $authenticated = false;
	private string $signature;
	private string $header;
	private string $payload;

	private array $jwt_header = [];
	private array $jwt_payload = [];

	function __construct() {
		$headers = getallheaders();
		$jwt = self::get_authorization($headers);
		if(substr_count($jwt,'.')!==2) {
			throw new \Exception('JWT malformed',400);
		}
		[$this->header, $this->payload, $signature] = explode('.', $jwt);

		$this->signature = self::jwt_decode($signature);
		$this->jwt_header = json_decode(self::jwt_decode($this->header),true);
		$this->jwt_payload = json_decode(self::jwt_decode($this->payload),true);

		if(empty($this->jwt_header['typ'])) {
			throw new \Exception('JWT header:typ not found',400);
		}
		if($this->jwt_header['typ'] !== 'JWT') {
			throw new \Exception('JWT header:typ != JWT not supported',400);
		}
		if(empty($this->jwt_header['alg'])) {
			throw new \Exception('JWT header:alg not found',400);
		}
		if($this->jwt_header['alg'] !== 'HS256') {
			throw new \Exception('JWT header:alg != HS256',400);
		}
		if(empty($this->jwt_payload['exp']) || !is_numeric($this->jwt_payload['exp'])) {
			throw new \Exception('JWT payload:exp not found',401);
		}
		if(time() >= $this->jwt_payload['exp']) {
			throw new \Exception('JWT payload:exp expired',401);
		}
		self::validate_body($this->jwt_payload['body_hash'] ?? null);
	}

	public static function get() : JWTVerify {
		$auth = new self();
		return $auth;
	}
	public static function validate_body(?string $body_hash) : void {
		if(empty($body_hash)) {
			throw new \Exception('JWT payload:body_hash not found',401);
		}
		$body_hash = explode(':',$body_hash);
		if(sizeof($body_hash)!==2) {
			throw new \Exception('JWT payload:body_hash invalid format',401);
		}
		list($algo,$user_string) = $body_hash;
		if($algo !== 'sha256'){
			throw new \Exception('JWT payload:body_hash invalid algorithm',401);
		}
		$known_string = hash_file($algo,'php://input');
		if(!hash_equals($known_string, $user_string)) {
			throw new \Exception('JWT payload:body_hash incorrect',401);
		}
	}
	public static function process(callable $callback) : JWTVerify {
		$auth = new self();
		$secret = $callback($auth);
		$auth->validate($secret);
		return $auth;
	}
	public function validate(#[\SensitiveParameter] string $secret) : void {
		$expected = hash_hmac('sha256',$this->header.'.'.$this->payload,$secret,true);
		if(!hash_equals($expected, $this->signature)) {
			throw new \Exception('JWT invalid signature',401);
		}
		$this->authenticated = true;
	}
	public function authenticated() : bool {
		return $this->authenticated;
	}

	public function payload(string $key) : string|int {
		if(!isset($this->jwt_payload[$key])) {
			throw new \Exception('JWT payload:'.$key.' not found',400);
		}
		return $this->jwt_payload[$key];
	}

	private static function jwt_decode(string $part) : string {
		$part .= str_repeat('=', (4 - strlen($part) % 4) % 4);
		$part = strtr($part, '-_', '+/');
		return base64_decode($part);
	}
}

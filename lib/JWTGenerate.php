<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

final class JWTGenerate extends CurlOpt {
	private string|false $secret;
	private int $expire;
	private array $jwt_header = [];
	private array $jwt_payload = [];
	private $body_hash_func;

	function __construct(#[\SensitiveParameter] $secret, int $expire = 30) {
		$this->secret = $secret;
		$this->expire = $expire;
	}

	function payload(string $key, string|int $value) : void {
		$this->jwt_payload[$key] = $value;
	}

	public function body_hash(callable $hashfunc) : void {
		$this->body_hash_func = $hashfunc;
	}

	public function curl_header() : array {
		$this->jwt_header['typ'] = 'JWT';
		$this->jwt_header['alg'] = 'HS256';

		$this->jwt_payload['iat'] = time();
		if($this->expire) {
			$this->jwt_payload['exp'] = time() + $this->expire;
		}

		if(!isset($this->body_hash_func)){
			throw new \Exception('Request type doesn\'t support body hashing required by JWT authorization');
		}
		$this->jwt_payload['body_hash'] = 'sha256:'.($this->body_hash_func)('sha256', hash_empty_body: true);

		$data = self::jwt_encode(json_encode($this->jwt_header));
		$data .= '.'.self::jwt_encode(json_encode($this->jwt_payload));
		$signuture = hash_hmac('sha256',$data,$this->secret,true);
		$jwt = $data.'.'.self::jwt_encode($signuture);
		return ['Authorization: Bearer '.$jwt];
	}

	private static function jwt_encode(string $part) : string {
		return rtrim(strtr(base64_encode($part), '+/', '-_'),'=');
	}
	public static function generate_secret(int $bytes = 48): string {
		return self::jwt_encode(random_bytes($bytes));
	}
}

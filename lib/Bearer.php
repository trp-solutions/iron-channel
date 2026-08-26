<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/TRP-Solutions/iron-channel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

final class Bearer extends CurlOpt implements Auth {
	use BearerTrait;

	private bool $authenticated = false;
	private string $token;
	private $body_hash_func;

	function __construct(#[\SensitiveParameter] string $token) {
		$this->token = $token;
	}

	public static function get() : Bearer {
		$headers = getallheaders();
		return new self(self::get_authorization($headers));
	}
	public static function process(callable $callback) : Bearer {
		$headers = getallheaders();
		$auth = new self(self::get_authorization($headers));
		unset($headers['Authorization']);
		$token = $callback($headers);
		if(empty($token)) {
			throw new \Exception('Bearer empty token',401);
		}
		$auth->validate($token);
		return $auth;
	}
	public function validate(#[\SensitiveParameter] string $token) : void {
		if(!hash_equals($this->token,$token)) {
			throw new \Exception('Bearer wrong token',403);
		}
		$this->authenticated = true;
	}

	public function authenticated() : bool {
		return $this->authenticated;
	}
	public function body_hash(callable $hashfunc) : void {
		$this->body_hash_func = $hashfunc;
	}
	public function curl_header() : array {
		$return = ['Authorization: Bearer '.$this->token];
		if(isset($this->body_hash_func)){
			$body_hash = ($this->body_hash_func)('sha256', hash_empty_body: false);
			if(!empty($body_hash)) {
				$return[] = 'X-Body-Hash: '.'sha256='.$body_hash;
			}
		}
		return $return;
	}
}

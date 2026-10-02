<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

final class BasicAuth extends CurlOpt implements Auth {
	private bool $authenticated = false;
	private string $id;
	private string $password;

	function __construct(string $id,#[\SensitiveParameter] string $password) {
		$this->id = $id;
		$this->password = $password;
	}
	public static function get() : BasicAuth {
		if(empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
			throw new \Exception('BasicAuth unauthorized',401);
		}
		return new self($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
	}
	public static function process(callable $callback) : BasicAuth {
		$auth = self::get();
		$id = $_SERVER['PHP_AUTH_USER'];
		$hash = $callback($id);
		if(empty($hash)) {
			throw new \Exception('BasicAuth empty process hash',401);
		}
		$auth->validate($id,$hash);
		return $auth;
	}
	public function validate(string $id,#[\SensitiveParameter] string $hash) : void {
		if(strcmp($this->id,$id)) {
			throw new \Exception('BasicAuth wrong id',403);
		}
		if(!password_verify($this->password, $hash)) {
			throw new \Exception('BasicAuth wrong password',403);
		}
		$this->authenticated = true;
	}
	public function authenticated() : bool {
		return $this->authenticated;
	}
	public function curl_setopt(\CurlHandle $ch) : void {
		curl_setopt($ch,CURLOPT_USERPWD, $this->id.':'.$this->password);
	}
}

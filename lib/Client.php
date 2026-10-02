<?php
/*
IronChannel is licensed under the Apache License 2.0 license
https://github.com/trp-solutions/IronChannel/blob/main/LICENSE
*/
declare(strict_types=1);
namespace TRP\IronChannel;

class Client {
	private static string $baseurl = '';
	private static array $option = [];
	private static Log|false $log = false;
	private static ?string $useragent = 'IronChannel (https://github.com/trp-solutions/IronChannel)';

	private string $url;
	private array $query = [];
	private array $header = [];
	private Data $request;
	private Auth|JWTGenerate|false $auth = false;
	private string|false $response;
	private string|false $response_type;

	function __construct(string $url,?Data $request = null) {
		$this->url = self::$baseurl.$url;
		$this->request = $request ?? new Get();
	}

	public function execute() : mixed {
		$ch = curl_init($this->url());

		if(
			$this->auth
			&& method_exists($this->auth, 'body_hash')
			&& $this->request instanceof DataHash
		){
			$this->auth->body_hash($this->request->curl_hash(...));
		}

		$header = $this->header;
		if(self::$useragent) {
			$header[] = 'User-Agent: '.self::$useragent;
		}
		array_push($header, ...$this->request->curl_header());
		if($this->auth) {
			array_push($header, ...$this->auth->curl_header());
		}
		if($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		$this->request->curl_setopt($ch);
		if($this->auth) {
			$this->auth->curl_setopt($ch);
		}
		foreach(self::$option as $option => $value) {
			curl_setopt($ch, $option, $value);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$this->response = curl_exec($ch);

		if(curl_errno($ch)) {
			$this->fail('curl_error: '.curl_error($ch),curl_errno($ch));
		}
		$response_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		$this->response_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

		if(self::$log) {
			self::$log->metrics('response_code',(string) $response_code);
			self::$log->metrics('content_type',$this->response_type);
			self::$log->metrics('time',(string) curl_getinfo($ch, CURLINFO_TOTAL_TIME));
		}

		if(!in_array($response_code,[200,201,202,204], true)) {
			$this->fail('response_code: '.$response_code,$response_code);
		}

		try {
			$response = $this->response(true);
		}
		catch(\Exception $e) {
			$this->fail('response_parse: '.$e->getMessage());
		}

		if(self::$log) {
			self::$log->finally(Result::Success);
		}
		return $response;
	}

	public function response(bool $parse = false) : mixed {
		if(!$parse) return $this->response;
		if($this->response_type===false) {
			return null;
		}
		if($this->response_type==='application/json') {
			return JSON::decode($this->response);
		}
		return $this->response;
	}
	public function fail(string $message, int $code = 0) : void {
		if(self::$log) {
			self::$log->log(LOG_ERR,$message);
			self::$log->finally(Result::Fail);
		}
		throw new \Exception($message,$code);
	}
	public function query(string $key, string|int $value) : void {
		$this->query[$key] = $value;
	}
	public static function log(Log|false $log) : void {
		self::$log = $log;
	}
	public function auth(Auth|JWTGenerate|false $auth) : void {
		$this->auth = $auth;
	}
	public function header(string $key, string $value) : void {
		$this->header[] = $key.': '.$value;
	}
	public static function setopt(int $option, mixed $value) : void {
		self::$option[$option] = $value;
	}
	public static function baseurl(string $baseurl) : void {
		self::$baseurl = $baseurl;
	}
	public static function useragent(?string $useragent) : void {
		self::$useragent = $useragent;
	}
	private function url() : string {
		if($this->query) {
			if(strpos($this->url,'?')!==false) {
				$url = $this->url.'&'.http_build_query($this->query);
			}
			else {
				$url = $this->url.'?'.http_build_query($this->query);
			}
		}
		else {
			$url = $this->url;
		}
		if(self::$log) {
			self::$log->log(LOG_DEBUG,'url: '.$url);
			self::$log->metrics('url',$url);
		}
		return $url;
	}
}

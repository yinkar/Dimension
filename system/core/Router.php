<?php

class Router {
	private static $_url;

	public function __construct($requestURL) {
		self::$_url = $requestURL;
	}

	public static function get($url, $callback) {
		/* Uygulanan method GET ise $url'i basitçe parse ederek gönderilen callback fonksiyonunu çalıştır */
		if($_SERVER['REQUEST_METHOD'] === 'GET') {
			$url = trim($url, '/');
			self::$_url = trim(self::$_url, '/');

			if(self::$_url == $url) {
				call_user_func($callback);
			}
		}
	}

	public static function post($url, $callback) {
		/* Uygulanan method POST ise $url'i basitçe parse ederek gönderilen callback fonksiyonunu çalıştır */
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			$url = trim($url, '/');
			self::$_url = trim(self::$_url, '/');

			if(self::$_url == $url) {
				call_user_func($callback);
			}
		}
	}
	
}
<?php
class RediSession {
	private static $redis;
	private static $session_id;
	private static $expire_time;
	private static $cookie_name;
	public function __construct($ip, $port, $auth = false, $expire_time = 86400, $cookie_name = 'RedisSESSID') {
		self::$redis = new Redis();
		self::$redis->connect($ip, $port);
		if ($auth) {
			self::$redis->auth($auth);
		}
		self::$session_id = md5(uniqid());
		self::$expire_time = $expire_time;
		self::$cookie_name = $cookie_name;
	}
	public function getid() {
		if (!isset($_COOKIE[self::$cookie_name])) {
			$session_id = md5(uniqid());
			setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
		} else {
			$session_id = $_COOKIE[self::$cookie_name];
		}
		return $session_id;
	}

	public function set($key, $value) {
		if (!isset($_COOKIE[self::$cookie_name])) {
			$session_id = md5(uniqid());
			setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
		} else {
			$session_id = $_COOKIE[self::$cookie_name];
		}
		$data = self::$redis->get($session_id);
		if (!$data) {
			$data = [];
		} else {
			$data = json_decode($data, true);
		}
		$data[$key] = $value;
		self::$redis->set($session_id, json_encode($data));
		return true;
	}

	public function unset($key) {
		if (!isset($_COOKIE[self::$cookie_name])) {
			$session_id = md5(uniqid());
			setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
		} else {
			$session_id = $_COOKIE[self::$cookie_name];
		}
		$data = self::$redis->get($session_id);
		if (!$data) {
			$data = [];
		} else {
			$data = json_decode($data, true);
		}
		unset($data[$key]);
		self::$redis->set($session_id, json_encode($data));
		return true;
	}

	public function get($key) {
		if (!isset($_COOKIE[self::$cookie_name])) {
			if (strlen($_COOKIE[self::$cookie_name]) !== 32) {
				return false;
			} else {
				$session_id = md5(uniqid());
				setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
			}
		} else {
			$session_id = $_COOKIE[self::$cookie_name];
		}
		$data = json_decode(self::$redis->get($session_id), true);
		return @$data[$key];
	}

	public function getAll() {
		if (!isset($_COOKIE[self::$cookie_name])) {
			if (strlen($_COOKIE[self::$cookie_name]) !== 32) {
				return false;
			} else {
				$session_id = md5(uniqid());
				setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
			}
		} else {
			$session_id = $_COOKIE[self::$cookie_name];
		}
		$data = json_decode(self::$redis->get($session_id), true);
		return $data;
	}
}
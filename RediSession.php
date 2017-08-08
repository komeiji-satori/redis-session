<?php
class RediSession {
	private static $redis;
	private static $session_id;
	private static $expire_time;
	private static $cookie_name;
	private static $custom_id;

	public function __construct($ip, $port, $auth = false, $expire_time = 86400, $cookie_name = 'RedisSESSID') {
		self::$redis = new Redis();
		self::$redis->connect($ip, $port);
		if ($auth) {
			self::$redis->auth($auth);
		}
		if (!self::$redis->ping()) {
			return false;
		}
		self::$session_id = $this->setid('__init');
		self::$expire_time = $expire_time;
		self::$cookie_name = $cookie_name;
	}
	public function setid($session_id = false) {
		if ($session_id == '__init') {
			self::$custom_id = false;
			return md5(uniqid());
		} else {
			self::$custom_id = true;
			self::$session_id = $session_id;
			return true;
		}

	}
	public function getid() {
		$session_id = $this->GetSessionID();
		return $session_id;
	}

	public function set($key, $value) {
		$session_id = $this->GetSessionID();
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
		$session_id = $this->GetSessionID();
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
	public function revoke($key) {
		$session_id = $this->GetSessionID();
		return self::$redis->delete($key);
	}

	public function get($key) {
		$session_id = $this->GetSessionID();
		$data = json_decode(self::$redis->get($session_id), true);
		return @$data[$key];
	}

	public function getAll() {
		$session_id = $this->GetSessionID();
		$data = json_decode(self::$redis->get($session_id), true);
		return $data;
	}
	private function GetSessionID() {
		if (!isset($_COOKIE[self::$cookie_name]) && self::$custom_id == false) {
			$session_id = md5(uniqid());
			setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
		} else {
			if (self::$custom_id == true) {
				$session_id = self::$session_id;
			} else {
				$session_id = $_COOKIE[self::$cookie_name];
			}
		}
		return $session_id;
	}
}
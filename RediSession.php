<?php
namespace RediSession;
use Redis;

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
		self::$expire_time = $expire_time;
		self::$cookie_name = $cookie_name;
	}

	public function getid() {
		$session_id = $this->GetSessionID();
		self::$redis->expire($session_id, self::$expire_time);
		return $session_id;
	}

	public function set($key = '', $value = '') {
		$session_id = $this->GetSessionID();
		self::$redis->expire($session_id, self::$expire_time);
		$data = self::$redis->get($session_id);
		if (!$data) {
			$data = [];
		} else {
			$data = json_decode($data, true);
		}
		if (gettype($key) == 'array') {
			foreach ($key as $keys => $value) {
				$data[$keys] = $value;
			}
		} else {
			$data[$key] = $value;
		}
		return self::$redis->set($session_id, json_encode($data));
	}

	public function unset($key) {
		$session_id = $this->GetSessionID();
		self::$redis->expire($session_id, self::$expire_time);
		$data = self::$redis->get($session_id);
		if (!$data) {
			$data = [];
		} else {
			$data = json_decode($data, true);
		}
		unset($data[$key]);
		return self::$redis->set($session_id, json_encode($data));
	}

	public function revoke($key) {
		return self::$redis->delete($key);
	}

	public function get($key) {
		$session_id = $this->GetSessionID();
		self::$redis->expire($session_id, self::$expire_time);
		$data = json_decode(self::$redis->get($session_id), true);
		return @$data[$key];
	}

	public function mget($keys_arr = []) {
		$result = self::$redis->mget($keys_arr);
		$return = [];
		foreach ($result as $key => $value) {
			$return[$keys_arr[$key]] = json_decode($value, true);
		}
		return $return;
	}

	public function mdel($keys_arr = []) {
		return self::$redis->del($keys_arr);
	}

	public function mset($data) {
		$result = [];
		foreach ($data as $key => $value) {
			$result[array_values($data[$key])[0]] = self::$redis->set(array_values($data[$key])[0], json_encode($value));
		}
		return $result;
	}

	public function getAll() {
		$session_id = $this->GetSessionID();
		self::$redis->expire($session_id, self::$expire_time);
		$data = json_decode(self::$redis->get($session_id), true);
		return $data;
	}

	private function GetSessionID() {
		if (isset($_COOKIE[self::$cookie_name])) {
			if (!self::$redis->exists($_COOKIE[self::$cookie_name])) {
				$session_id = md5(uniqid());
				self::$session_id = $session_id;
				self::$redis->set($session_id, '');
				setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
			} else {
				$session_id = $_COOKIE[self::$cookie_name];
				self::$session_id = $session_id;
			}
		} else {
			if (isset(self::$session_id)) {
				return self::$session_id;
			} else {
				$session_id = md5(uniqid());
				self::$session_id = $session_id;
				setcookie(self::$cookie_name, $session_id, time() + self::$expire_time);
			}
		}
		return $session_id;
	}
}
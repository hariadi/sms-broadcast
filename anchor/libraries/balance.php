<?php

class Balance {

	public static function update() {
		// first time
		if( !Config::meta('update_balance')) {
			static::check('setup');
		} else {
			static::check();
		}
	}

	public static function check($type = 'update') {
		$balance = static::touch();
		$today = date('Y-m-d H:i:s');
		$table = Base::table('meta');

		if ($type === 'setup') {
			Query::table($table)->insert(array('key' => 'update_balance', 'value' => $balance));
		} elseif (($type === 'update')) {
			Query::table($table)->where('key', '=', 'update_balance')->update(array('value' => $balance));
		}

		// reload database metadata
		foreach(Query::table($table)->get() as $item) {
			$meta[$item->key] = $item->value;
		}

		Config::set('meta', $meta);
	}


	public static function touch() {
		$url = 'https://isms.com.my/isms_balance.php?un=' . Config::meta('isms_username') . '&pwd=' .Config::meta('isms_password');

		if(in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
			try {
				$context = stream_context_create(array('http' => array('timeout' => 2)));
				$result = file_get_contents($url, false, $context);
			} catch(Exception $e) {
				$result = false;
			}
		}
		else if(function_exists('curl_init')) {
			$session = curl_init();

			curl_setopt_array($session, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true
			));

			$result = curl_exec($session);

			curl_close($session);
		}
		else {
			$result = false;
		}

		return $result;
	}

}
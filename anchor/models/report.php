<?php

class Report extends Base {

	public static $table = 'reports';

	public static function update() {
		// first time
		if( !$last_update = Config::meta('last_report_sync')) {
			static::check('setup');
		} else {
			static::check('update', $last_update);
		}
	}

	public static function check($type = 'update', $last_update = null) {

		$last_update = new DateTime(Date::format($last_update, 'Y-m-d'));
		$today = new DateTime(date('Y-m-d'));
		$today = $today->modify( '+1 day' );
		$meta = Base::table('meta');

		if ($type === 'setup') {
			Query::table($meta)->insert(array('key' => 'last_report_sync', 'value' => '2013-12-09'));
		}

		$period = new DatePeriod(
			$last_update,
		  new DateInterval('P1D'),
		  $today
		);

		foreach( $period as $date) {
			
			$reports = static::touch($date->format('Y-m-d'));

			if ($reports) {
				foreach( static::formatReport($reports) as $report) {
					
					$insert = false;
					// check report last id

					$report_id = Query::table(static::table())->where('report', '=', $report['id'])->take(1)->column(array('report'));

					if (! $report_id) {
						$report['report'] = $report['id'];
						unset($report['id']);
						$report['created'] = Date::mysql('now');

						$query = Query::table(static::table())->insert($report);
						Query::table($meta)->where('key', '=', 'last_report_sync')->update(array('value' => $report['date']));
					}
				}
			}
		}
		
		
	}

	public static function touch($date) {
		$url = 'https://isms.com.my/api_sms_history.php?un=' . Config::meta('isms_username') . '&pwd=' .Config::meta('isms_password') . '&date=' . $date;

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

	private static function formatReport($data)
	{
		$value = array();
		if (!empty($data)) {
			$reports = explode("||", $data);
			$keys = array('id', 'destination', 'message', 'charge', 'type', 'date', 'status');
			foreach ($reports as $pieces) {
				$value[] = array_combine($keys, explode("|@|", $pieces));
			}
		}
		return $value;
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/reports'));
	}

}
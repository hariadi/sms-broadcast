<?php

/**
 * Class for isms.com.my
 *
 * @package isms
 * @author  Hariadi Hinta <diperakui@yahoo.com>
 * @link    https://github.com/hariadi/isms
 * @license MIT
 * @version 0.1.0
 */

//namespace Isms;

class Isms
{
	const VERSION  = '0.1.0';
	const HOST     = 'https://isms.com.my/';
	const SEND     = 'isms_send.php?';
	const BALANCE  = 'isms_balance.php?';
	const SCHEDULE = 'isms_scheduler.php?';
	const REPORT 	 = 'api_sms_history.php?';
	//api_sms_history.php?un=username&pwd=password&date=2013-12-16

	private $_login;
	private $_password;
	private $_auth;
	private $_sender;
	private $_message;
	private $_type;
	private $_keyword;
	private $_limit = 300;
	private $_format = 'Y-m-d';
	private $_timezone = 'Asia/Kuala_Lumpur';
	private $_schedule = null;
	protected $to = array();
	protected $response_code = array(
		'2000' => 'SUCCESS - Message Sent.',
		'-1000' => 'UNKNOWN ERROR - Unknown error. Please contact the administrator.',
		'-1001' => 'AUTHENTICATION FAILED - Your username or password are incorrect.',
		'-1002' => 'ACCOUNT SUSPENDED / EXPIRED - Your account has been expired or suspended. Please contact the administrator.',
		'-1003' => 'IP NOT ALLOWED - Your IP is not allowed to send SMS. Please contact the administrator.',
		'-1004' => 'INSUFFICIENT CREDITS - You have run our of credits. Please reload your credits.',
		'-1005' => 'INVALID SMS TYPE - Your SMS type is not supported.',
		'-1006' => 'INVALID BODY LENGTH (1-900) - Your SMS body has exceed the length. Max = 900',
		'-1007' => 'INVALID HEX BODY - Your Hex body format is wrong.',
		'-1008' => 'MISSING PARAMETER - One or more required parameters are missing.'
	);

	public function  __construct( $login = null, $pwd = null )
	{
		$this->_login = $login;
		$this->_password = $pwd;
		$this->_sender = '63633';
		$this->_keyword = 'JOBSMY';
		$this->_type = 1;
		$this->_auth = $this->getAuthParams();
	}

	public function setNumber($number)
  {
    return $this->addAnNumber($number);
  }

  public function setKeyword($keyword)
  {
    return $this->_keyword = $keyword;
  }

  public function setMessage($msg)
  {
    return $this->_message = $this->_keyword . ': '. rawurlencode($msg);
  }

  public function schedule($start, $trigger, $description, $week, $month, $day, $sid = null, $action = null)
  {
  	$schedule = array();
  	$schedule['start'] = date_parse($start);
		$schedule['date'] = date('Y-m-d', strtotime($start));
  	$schedule['description'] = $description;
  	$schedule['trigger'] = $trigger;
  	$schedule['hour'] = date('H', strtotime($start));
  	$schedule['minute'] = $this->normalizeMinute($start);
  	$schedule['week'] = $week;
  	$schedule['month'] = $month;
  	$schedule['day'] = $day;

  	if ($sid) {
  		$schedule['scid'] = $sid;
  		$schedule['action'] = $action;
  	}
  	
  	$this->_schedule = $schedule;
  	return $this->_schedule;
  }

  public function getMessage()
  {
    return $this->_message;
  }

  public function getNumber()
  {
    return $this->_to;
  }

  public function viewSMSParams()
  {
    return $this->getSMSParams();
  }

  public function normalize($number)
  {
    return $this->normalizeNumber($number);
  }

	public function send()
	{	
		$schedule = false;	
		$url = self::HOST . self::SEND;
		$params = $this->_auth;

		// schedule?
		if ($this->_schedule) {
			$url = self::HOST . self::SCHEDULE;
			if (array_key_exists('scid', $this->_schedule)) {
				$params['scid'] = $this->_schedule['scid'];
				$params['action'] = 'update';
			}

			$params['date'] = $this->_schedule['date'];
			$params['det'] = $this->_schedule['description'];
			$params['tr'] = $this->_schedule['trigger'];
			$params['hour'] = $this->_schedule['hour'];
			$params['min'] = $this->_schedule['minute'];
			$params['week'] = $this->_schedule['week'];
			$params['month'] = $this->_schedule['month'];
			$params['day'] = $this->_schedule['day'];
			$schedule = true;
		}
		
		$params['msg'] = $this->_message;
		$params['type'] = $this->_type;
		$params['sendid'] = $this->_sender;

		$destination = array();
		$curls = array();

		if (!empty($this->_to)) {
			$destination = array_chunk($this->_to, $this->_limit);
			foreach ($destination as $key => $value) {
				$params['dstno'] = $this->formatNumber($value);
				$curls[] = array('url' => $url, 'post' => $params);
			}
		}

		$results = $this->curl( $curls );
		
		$response = array();
		foreach ($results as $id => $result) {
			$response[] = array(
				'raw' => $result, 
				'code' => $this->getCode($result),
				'description' => $this->getAnswer($this->getCode($result)
			));
		}

		return $response;
	}

	public function delete($scid)
	{
		$url = self::HOST . self::SCHEDULE;
		$params = $this->_auth;
		$params['scid'] = $scid;
		$params['action'] = 'delete';
		$curls = array('url' => $url, 'post' => $params);
		$result = $this->curl( $curls );
		return $this->getCode($result[0]);
	}

	public function balance()
	{
		$url = self::HOST . self::BALANCE;
		$params = $this->_auth;
		$curls = array('url' => $url, 'post' => $params);
		$result = $this->curl( $curls );
		return $this->getCode($result[0]);
	}

	public function report($date)
	{
		$url = self::HOST . self::REPORT;
		$params = $this->_auth;

		if (!is_array($date)) {
			$tmpdate = $date;
			$date = array($this->formatDate($tmpdate));
		}

		foreach ($date as $dt) {
			$params['date'] = $dt;
			$curls[] = array('url' => $url, 'post' => $params);
		}
		
		$result = $this->curl( $curls );
		print_r($curls);
		exit();
		return $this->formatReport($result);
	}

	private function addAnNumber($number)
	{
		if (is_array($number)) {
			foreach ($number as $num)
	    {
	      $this->_to[] = $num;
	    }
		} else {
			$this->_to[] = $number;
		}
		
	}

	function normalizeMinute($timestring) {
	    $minutes = date('i', strtotime($timestring));
	    return $minutes - ($minutes % 15);
	}

	private function normalizeNumber($number, $countryCode = 60)
  {
  	if (isset($number)) {
  		$number = trim($number);
  		$number = str_replace("+", "", $number);
  		preg_match( '/(0|\+?\d{2})(\d{8,9})/', $number, $matches);
			if ((int) $matches[1] === 0 ) {
				$number = $countryCode . $matches[2];
			}
  	}
    return $number;
  }

  private function formatReport($data)
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

  private function formatNumber($number)
	{
		$format = "";
		if (is_array($number)) {
			$format = implode(";", $number);
		}
		return $format;
	}

	private function validDate($date, $format = 'Y-m-d H:i:s')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	private function formatDate($date = null, $format = null) {
		
		if (is_null($date)) {
			$date = 'now';
		}
		if(is_null($format)) {
			$format = $this->_format;
		}

		$date = new DateTime($date, new DateTimeZone('GMT'));
		$date->setTimezone(new DateTimeZone($this->_timezone));

		return $date->format($format);
	}

	private function getCode($result)
	{
		return preg_replace("/[^0-9.-]/", "", $result);
	}

	private function getAuthParams()
	{
		$params['un'] = $this->_login;
		$params['pwd'] = $this->_password;
		return $params;
	}

	private function getSMSParams()
	{		
		$params['dstno'] = $this->formatNumber($this->_to);
		$params['type'] = $this->_type;
		$params['msg'] = $this->_message;
		$params['sendid'] = $this->_sender;
		return $params;
	}

	private function getAnswer( $code )
	{
		if ( isset( $this->response_code[$code] ) ) {
			return $this->response_code[$code];
		}
	}

	private function curl($data) {
 
	  // array of curl handles
	  $curly = array();
	  // data to be returned
	  $result = array();
	 
	  // multi handle
	  $mh = curl_multi_init();
	 
	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($data as $id => $d) {
	 
	    $curly[$id] = curl_init();
	 
	    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;

	    $options = array(
	    	CURLOPT_RETURNTRANSFER => 1,
	    	CURLOPT_URL => $url,
	    	CURLOPT_HEADER         => 0,
	    	CURLOPT_ENCODING       => "",
				CURLOPT_SSL_VERIFYHOST => 0,
	    	CURLOPT_SSL_VERIFYPEER => 0,
			);

	    // it is post?
			if (is_array($d)) {
	      if (!empty($d['post'])) {
	      	$options[CURLOPT_POST] = 1;
	      	$options[CURLOPT_POSTFIELDS] = $d['post'];
	      }
	    }

	 		curl_setopt_array($curly[$id], $options);
	    curl_multi_add_handle($mh, $curly[$id]);
	  }
	 
	  // execute the handles
	  $running = null;
	  do {
	    curl_multi_exec($mh, $running);
	  } while($running > 0);
	 
	 
	  // get content and remove handles
	  foreach($curly as $id => $c) {
	    $result[$id] = curl_multi_getcontent($c);
	    curl_multi_remove_handle($mh, $c);
	  }
	 
	  // all done
	  curl_multi_close($mh);
	 
	  return $result;
	}
}
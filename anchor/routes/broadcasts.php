<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Broadcasts
	*/
	Route::get(array('admin/broadcasts', 'admin/broadcasts/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['broadcasts'] = Broadcast::paginate($page, Config::get('meta.posts_per_page'));
		$vars['status'] = 'all';

		return View::create('broadcasts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List broadcasts by status and paginate through them
	*/
	Route::get(array(
		'admin/broadcasts/status/(:any)',
		'admin/broadcasts/status/(:any)/(:num)'), function($status, $page = 1) {

		$query = Broadcast::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('broadcasts.client'));

		if (Auth::user()->role != 'administrator') {
			$query = $query->where(Base::table('broadcasts.client'), '=', Auth::user()->id);
		}

		$total = $query->count();
		
		$query = $query->where(Base::table('broadcasts.status'), '=', $status);

		$per_page = Config::meta('posts_per_page');
		
		$broadcasts = $query->sort('created')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(array(Base::table('broadcasts.*'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));

		$url = Uri::to('admin/broadcasts/status');

		$pagination = new Paginator($broadcasts, $total, $page, $per_page, $url);

		$vars['messages'] = Notify::read();
		$vars['broadcasts'] = $pagination;
		$vars['status'] = $status;

		return View::create('broadcasts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Broadcast
	*/
	Route::get('admin/broadcasts/view/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['broadcast'] = Broadcast::get('id', $id);

		return View::create('broadcasts/view', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Add Broadcast
	*/
	Route::get('admin/broadcasts/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['triggers'] = array(
			'onetime' => __('broadcasts.onetime'),
			'daily' => __('broadcasts.daily'),
			'weekly' => __('broadcasts.weekly'),
			'monthly' => __('broadcasts.monthly')
		);

		return View::create('broadcasts/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/broadcasts/add', function() {

		$input = Input::get(array('sender', 'recipient', 'fromfile', 'message'));
		$transaction = array();
		$recipients = array();
		$input['fromfile'] = $_FILES['fromfile'];
		$broadcasts = false;
		$broadcasts_schedule = false;

		if($schedule = Input::get('schedule')) {
			$input['schedule'] = $schedule;
			$input['trigger'] = Input::get('trigger');
			$broadcasts_schedule = true;
		}

		if(empty($input['sender'])) {
			$input['sender'] = '63663';
		}

		if(!empty($input['recipient'])) {
			$recipient = $input['recipient'];
			unset($input['recipient']);
			$input['recipient'] = array();

			if (strpos($recipient, ',') !== false) {
			  $input['recipient'] = explode(',', $recipient);
			} else {
				$input['recipient'][] = $recipient;
			}
			$broadcasts = true;
		}

		$validator = new Validator($input);

		if($input['fromfile']['error'] === 0) {

			$upload = Upload::factory(PATH . 'content');
			$upload->file($input['fromfile']);
			//$upload->set_filename();
			$upload->set_max_file_size((int) (ini_get('upload_max_filesize')));
			$upload->set_allowed_mime_types(array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/excel', 'application/vnd.ms-excel'));
			$results = $upload->upload();

			if(count($results['errors']) > 0) {
				Notify::add('error', implode(', ', $results['errors']));
				return Response::redirect('admin/broadcasts/add');
			}

			$input['file'] = $results['filename'];

			//no error
			require PATH . 'vendor/nuovo/spreadsheet-reader/php-excel-reader/excel_reader2' . EXT;
			require PATH . 'vendor/nuovo/spreadsheet-reader/SpreadsheetReader' . EXT;

			$Reader = new SpreadsheetReader($results['full_path']);
			$data = array();
			foreach ($Reader as $Row)
	    {
	      $input['recipient'][] = $Row[0];
	    }
	    $data = normalize_number($input['recipient']);
	    $input['recipient'] = $data;
	    $broadcasts = true;
		}

		//recipients = array_merge($input['recipient'], normalize_number($data));
		$recipients = $input['recipient'];
	  $input['recipient'] = Json::encode($recipients);

		$fromfile = $input['fromfile'];
		unset($input['fromfile']);

		if(!count($fromfile) > 0 ) {

			$validator->check('recipient')
			->is_max(3, __('broadcasts.recipient_missing'));

		}

		$validator->check('message')
			->is_max(3, __('broadcasts.message_missing'));
		

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::danger($errors);

			return Response::redirect('admin/broadcasts/add');
		}

		$user = Auth::user();
		$input['client'] = $user->id;
		$input['status'] = 'success';
		$input['created'] = Date::mysql('now');

		if ($broadcasts) {
			// deduct credit
			$transaction['client'] = $user->id;
			$transaction['guid'] = User::where('id', '=', $user->id)->column(array('credit'));
			$transaction['quantity'] = count($recipients, COUNT_RECURSIVE);
			$transaction['credit'] = (float) -abs(Config::meta('credit_per_sms') * $transaction['quantity']);
			$transaction['created'] = $input['created'];

			Transaction::create($transaction);

			// everything setup, lets send some sms
			$sms = new Isms(Config::meta('isms_username'), Config::meta('isms_password'));
			$sms->setMessage($input['message']);
			$sms->setNumber($recipients);

			if ($broadcasts_schedule) {
				$sms->schedule($input['schedule']);
				$sms->trigger($input['trigger']);
				$responses = $sms->schedule();
			} else {
				$responses = $sms->send();
			}
			
			//we don't want to check it fail or not. always success in system :). we do in isms api report
			//$input['status'] = 'pending';
			$input['reason'] = Json::encode($responses);
			//print_r($responses);
		}
		//exit();
		

		Broadcast::create($input);

		Notify::success(__('broadcasts.created'));

		return Response::redirect('admin/broadcasts');
	});


});

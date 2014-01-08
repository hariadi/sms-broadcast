<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Schedules
	*/
	Route::get(array('admin/schedules', 'admin/schedules/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['schedules'] = Schedule::paginate($page, Config::get('meta.posts_per_page'));
		$vars['status'] = 'all';

		return View::create('schedules/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Schedule
	*/
	Route::get('admin/schedules/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['schedule'] = Schedule::view($id);

		$vars['schedules'] = array(
			'onetime' => __('broadcasts.onetime'),
			'daily' => __('broadcasts.daily'),
			'weekly' => __('broadcasts.weekly'),
			'monthly' => __('broadcasts.monthly')
		);

		$vars['statuses'] = array(
			'published' => __('global.published'),
			'draft' => __('global.draft'),
			'archived' => __('global.archived')
		);

		return View::create('schedules/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer', $vars);
	});

	Route::post('admin/schedules/edit/(:num)', function($id) {

		$input = Input::get(array('broadcast', 'sender', 'keyword', 'recipient', 'message', 'schedule', 'description', 'start_date', 'weekdays', 'monthly', 'days'));

		$file_upload = false;
		$broadcasts = array();
		$transaction = array();
		$recipients = array();
		$schedules = array();

		$schedules['schedule'] = $input['schedule'];

		if($schedules['schedule'] != 'onetime') {

			$schedules['start'] = $input['start_date'] ?: Date::mysql('now');
			$schedules['description'] = $input['description'];

			switch ($schedules['schedule']) :

				case 'daily':

					$schedules['week'] = range_number(array(), 1, 7);
					$schedules['month'] = range_number(array(), 1, 12);
					$schedules['day'] = range_number(array(), 1, 31);

					break;

				case 'weekly':

					$schedules['week'] = range_number(Input::get('weekdays'), 1, 7);
					$schedules['month'] = range_number(array(), 1, 12);
					$schedules['day'] = range_number(array(), 1, 31);

					break;

				case 'monthly':

					$schedules['week'] = range_number(array(), 1, 7);
					$schedules['month'] = range_number(Input::get('monthly'), 1, 12);
					$schedules['day'] = range_number(Input::get('days'), 1, 31);

					break;

				default:
					break;
			endswitch;
		}

		$recipient = $input['recipient'];
		$input['recipient'] = array();
		if (strpos($recipient, ',') !== false) {
		  $input['recipient'] = explode(',', $recipient);
		} else {
			$input['recipient'][] = $recipient;
		}

		// let's check for file upload
		if($fromfile = $_FILES['fromfile'] and $fromfile['error'] === 0) {
			$input['fromfile'] = $fromfile;
			$file_upload = true;

			$upload = Upload::factory(PATH . 'content');
			$upload->file($input['fromfile']);
			$upload->set_max_file_size((int) (ini_get('upload_max_filesize')));
			$upload->set_allowed_mime_types(array(
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
				'application/excel', 
				'application/vnd.ms-excel'));
			$results = $upload->upload();

			if(count($results['errors']) > 0) {
				Notify::add('error', implode(', ', $results['errors']));
				return Response::redirect('admin/schedules/edit/' . $id);
			}

			require PATH . 'vendor/nuovo/spreadsheet-reader/php-excel-reader/excel_reader2' . EXT;
			require PATH . 'vendor/nuovo/spreadsheet-reader/SpreadsheetReader' . EXT;

			$Reader = new SpreadsheetReader($results['full_path']);
			$data = array();
			foreach ($Reader as $Row) :
	      $input['recipient'][] = $Row[0];
	    endforeach;
	    $data = normalize_number($input['recipient']);
	    $input['recipient'] = $data;

	    $broadcasts['file'] = $results['filename'];
		}

		$recipients = $input['recipient'];
		$input['recipient'] = Json::encode($recipients);

		$validator = new Validator($input);

		$validator->check('start_date')
			->is_max(3, __('broadcasts.start_date_missing'));

		$validator->check('message')
			->is_max(3, __('broadcasts.message_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::danger($errors);

			return Response::redirect('admin/broadcasts/add');
		}

		// Transaction
		$user = Auth::user();
		$transaction['client'] = $user->id;
		$transaction['guid'] = User::where('id', '=', $user->id)->column(array('credit'));
		$transaction['quantity'] = count($recipients, COUNT_RECURSIVE);
		$transaction['credit'] = (float) -abs(Config::meta('credit_per_sms') * $transaction['quantity']);
		$transaction['created'] = Date::mysql('now');

		Transaction::create($transaction);

		$sms = new Isms(Config::meta('isms_username'), Config::meta('isms_password'));
		$sms->setMessage($input['message']);
		$sms->setNumber($recipients);

		if($input['keyword']) {
			$sms->setKeyword($input['keyword']);
		}

		$sms->schedule(
			$schedules['start'], 
			$schedules['schedule'], 
			$schedules['description'], 
			$schedules['week'], 
			$schedules['month'], 
			$schedules['day'],
			$id,
			'update'
		);

		$responses = $sms->send();
		$broadcasts['reason'] = Json::encode($responses);

		// Update broadcast
		$broadcasts['id'] = $input['broadcast'];
		$broadcasts['client'] = $user->id;
		$broadcasts['sender'] = $input['sender'];
		$broadcasts['recipient'] = $input['recipient'];
		$broadcasts['keyword'] = $input['keyword'];
		$broadcasts['message'] = $input['message'];
		$broadcasts['status'] = 'success';

		Broadcast::update($broadcasts['id'], $broadcasts);
		Notify::success(__('broadcasts.updated'));

		$schedules['broadcast'] = $broadcasts['id'];
		
		Schedule::update($id, $schedules);
		Notify::success(__('schedules.updated'));


		return Response::redirect('admin/schedules');
	});

	/*
		Add Schedule
	*/
	Route::get('admin/schedules/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['triggers'] = array(
			'onetime' => __('schedules.onetime'),
			'daily' => __('schedules.daily'),
			'weekly' => __('schedules.weekly'),
			'monthly' => __('schedules.monthly')
		);

		return View::create('schedules/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

});

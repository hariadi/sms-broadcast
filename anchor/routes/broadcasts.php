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
	Route::get('admin/broadcasts/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['broadcast'] = Broadcast::get('id', $id);

		return View::create('broadcasts/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/broadcasts/edit/(:num)', function($id) {
		$input = Input::get(array('sender', 'recipient', 'message'));

		$validator = new Validator($input);

		$validator->check('sender')
			->is_max(3, __('broadcasts.sender_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/broadcasts/edit/' . $id);
		}

		if(empty($input['recipient'])) {
			$input['recipient'] = $input['sender'];
		}

		$input['recipient'] = recipient($input['recipient']);

		Broadcast::update($id, $input);

		Notify::success(__('broadcasts.updated'));

		return Response::redirect('admin/broadcasts/edit/' . $id);
	});

	/*
		Add Broadcast
	*/
	Route::get('admin/broadcasts/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('broadcasts/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/broadcasts/add', function() {

		$input = Input::get(array('sender', 'recipient', 'fromfile', 'message'));
		$recipients = array();
		$input['fromfile'] = $_FILES['fromfile'];

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

			
		}

		$validator = new Validator($input);

		if(count($input['fromfile']) > 0) {

			$upload = Upload::factory(PATH . 'content');
			$upload->file($input['fromfile']);
			//$upload->set_filename();
			$upload->set_max_file_size((int)(ini_get('upload_max_filesize')));
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
	      $data[] = $Row[0];
	    }

	    $recipients = array_merge($input['recipient'], normalize_number($data));
	    $input['recipient'] = Json::encode($recipients);

	    
		}

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

			Notify::error($errors);

			return Response::redirect('admin/broadcasts/add');
		}

		$user = Auth::user();
		$input['client'] = $user->id;
		$input['status'] = 'success';

		Broadcast::create($input);

		Notify::success(__('broadcasts.created'));

		return Response::redirect('admin/broadcasts');
	});

	/*
		Delete Broadcast
	*/
	Route::get('admin/broadcasts/delete/(:num)', function($id) {
		$total = Broadcast::count();

		if($total == 1) {
			Notify::error(__('broadcasts.delete_error'));

			return Response::redirect('admin/broadcasts/edit/' . $id);
		}

		// move posts
		$broadcast = Broadcast::where('id', '<>', $id)->fetch();

		// delete selected
		Broadcast::find($id)->delete();

		// update posts
		Post::where('broadcast', '=', $id)->update(array(
			'broadcast' => $broadcast->id
		));

		Notify::success(__('broadcasts.deleted'));

		return Response::redirect('admin/broadcasts');
	});

});

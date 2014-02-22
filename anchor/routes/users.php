<?php

Route::collection(array('before' => 'auth,admin,csrf'), function() {

	/*
		List users
	*/
	Route::get(array('admin/users', 'admin/users/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['users'] = User::paginate($page, Config::get('meta.posts_per_page'));
		$vars['status'] = 'all';

		return View::create('users/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/users/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();		
		$vars['user'] = User::find($id);

		$topupid = Topup::where('client', '=', $id)->sort('created', 'desc')->take(1)->column(array('id'));

		$credit_avail = User::where('id', '=', $id)->column(array('credit'));
		$credit_use = Broadcast::where('client', '=', $id)->where('topup', '=', $topupid)->sum('credit');

		$expired = Credit::where('client', '=', $id)->column(array('expired'));

		if ($expired == '0000-00-00 00:00:00' || empty($expired)) {
			$expired_date = new DateTime(Date::mysql('now'));;
	      	$expired_date->modify('+3 month');
	      	$expired = $expired_date->format('Y-m-d H:i:s');
		}

		$vars['user']->expired = $expired;

		$vars['credit'] = array(
			'available' => (int) $credit_avail,
			'used' => (int) $credit_use
		);
		
		$vars['fields'] = Extend::fields('user', $id);

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'client' => __('global.client')
		);
		
		return View::create('users/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/edit/(:num)', function($id) {
		$input = Input::get(array('username', 'email', 'real_name', 'bio', 'status', 'role', 'credit', 'current_credit'));
		$password_reset = false;
		$topup_reset = false;
		$topup = array();

		$input['created'] = Date::mysql('now');

		if($password = Input::get('password')) {
			$input['password'] = $password;
			$password_reset = true;
		}

		if (!empty($input['credit'])) {
			$input['credit'] = intval($input['credit']);
			$topup['client'] = $id;
			$topup['created'] = $input['created'];
			$topup['expired'] = Input::get('expired');
			$topup['createdby'] = Auth::user()->id;
			$topup_reset = true;
		}

		// User
		$input['credit'] = (float) $input['current_credit'] + $input['credit'];
		unset($input['current_credit']);
		

		$validator = new Validator($input);

		$validator->add('safe', function($str) use($id) {
			return ($str != 'inactive' and Auth::user()->id == $id);
		});

		$validator->check('username')
			->is_max(2, __('users.username_missing', 2));

		$validator->check('email')
			->is_email(__('users.email_missing'));

		if($password_reset) {
			$validator->check('password')
				->is_max(6, __('users.password_too_short', 6));
		}

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::danger($errors);

			return Response::redirect('admin/users/edit/' . $id);
		}

		if($password_reset) {
			$input['password'] = Hash::make($input['password']);
		}

		User::update($id, $input);
		Extend::process('user', $id);

		if($topup_reset) {
			$topup['credit'] = $input['credit'];
			Topup::create($topup);
			Notify::success(__('users.topup'));
		}

		// update TransRec account but not when TranRec account get top-up
		if ($id != 1) {
			// get TransRec balance
			$master_credit = User::where('id', '=', 1)->column(array('credit'));

			$transrec = array();
			$transrec['credit'] = (float) $master_credit - Input::get('credit');

			// update transrec balance
			//Credit::create($transrec);
			User::update(1, $transrec);

		}

		Notify::success(__('users.updated'));

		return Response::redirect('admin/users/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/users/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$vars['fields'] = Extend::fields('user');

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'client' => __('global.client')
		);

		return View::create('users/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/add', function() {
		$input = Input::get(array('username', 'email', 'real_name', 'password', 'bio', 'credit', 'status', 'role'));

		$topup_reset = false;
		$topup = array();

		$input['created'] = Date::mysql('now');

		if (empty($input['credit'])) {
			$input['credit'] = 0;
		}
		if ($input['credit'] > 0) {

			$topup['created'] = $input['created'];
			$topup['createdby'] = Auth::user()->id;
			$topup['credit'] = $input['credit'];
			$topup['expired'] = Input::get('expired');
			$topup_reset = true;
		}

		$validator = new Validator($input);

		$validator->check('username')
			->is_max(2, __('users.username_missing', 2));

		$validator->check('email')
			->is_email(__('users.email_missing'));

		$validator->check('password')
			->is_max(6, __('users.password_too_short', 6));

		if($errors = $validator->errors()) {
			Input::flash();
			Notify::danger($errors);
			return Response::redirect('admin/users/add');
		}
		$input['password'] = Hash::make($input['password']);

		$user = User::create($input);
		Extend::process('user', $user->id);
		Notify::success(__('users.created'));

		if($topup_reset) {
			$topup['client'] = $user->id;
			Topup::create($topup);
			Notify::success(__('users.topup'));
		}

		return Response::redirect('admin/users');
	});

	/*
		Delete user
	*/
	Route::get('admin/users/delete/(:num)', function($id) {
		$self = Auth::user();

		if($self->id == $id) {
			Notify::danger(__('users.delete_error'));

			return Response::redirect('admin/users/edit/' . $id);
		}

		User::where('id', '=', $id)->delete();

		Notify::success(__('users.deleted'));

		return Response::redirect('admin/users');
	});

});
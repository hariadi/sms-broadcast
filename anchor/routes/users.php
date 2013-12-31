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

		$uuid = $vars['user']->credit;

		$credit_avail = Credit::where('client', '=', $id)->where('uuid', '=', $uuid)->column(array('credit'));
		$credit_use = Transaction::where('client', '=', $id)->where('guid', '=', $uuid)->sum('credit');


		$vars['credit'] = array(
			'available' => $credit_avail,
			'used' => $credit_use,
			'balance' => (int) $credit_avail + $credit_use
		);
		
		$vars['fields'] = Extend::fields('user', $id);

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'user' => __('global.user')
		);
		
		return View::create('users/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/edit/(:num)', function($id) {
		$input = Input::get(array('username', 'email', 'real_name', 'bio', 'status', 'role'));
		$password_reset = false;
		$credit_topup = false;

		$credit = array();

		if($password = Input::get('password')) {
			$input['password'] = $password;
			$password_reset = true;
		}

		if($topup = Input::get('credit')) {

			$credit['client'] = $id;
			$credit['uuid'] = UUID::v4();
			$input['credit'] = $credit['uuid'];
			$credit['createdby'] = Auth::user()->id;
			$credit['credit'] = (float) $topup + Input::get('current_credit');
			$credit_topup = true;

		}

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

		if ($credit_topup) {
			Credit::create($credit);
			Notify::success(__('users.topup'));
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
			'user' => __('global.user')
		);

		return View::create('users/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/add', function() {
		$input = Input::get(array('username', 'email', 'real_name', 'password', 'bio', 'status', 'role'));

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
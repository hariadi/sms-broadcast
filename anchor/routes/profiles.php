<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List users
	*/
	Route::get('admin/profiles', function() {
		$vars['messages'] = Notify::read();
		$vars['user'] = Dashboard::view(Auth::user()->id);
		//$vars['user'] = User::find(Auth::user()->id);
		$uuid = $vars['user']->credit;

		$credit_avail = Credit::where('client', '=', Auth::user()->id)->column(array('credit'));
		$credit_use = Transaction::where('client', '=', Auth::user()->id)->where('guid', '=', $uuid)->sum('credit');

		$vars['credits'] = array(
			'available' => $credit_avail,
			'used' => $credit_use,
			'balance' => $credit_avail + $credit_use
		);

		return View::create('profiles/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/profiles/edit', function() {
		$id = Auth::user()->id;
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

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'user' => __('global.user')
		);

		return View::create('profiles/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/profiles/edit', function() {
		$id = Auth::user()->id;
		$input = Input::get(array('username', 'email', 'real_name', 'bio', 'status', 'role'));
		$password_reset = false;
		$avatar_reset = false;

		if($password = Input::get('password')) {
			$input['password'] = $password;
			$password_reset = true;
		}

		if($avatar = Input::get('avatar')) {
			$input['avatar'] = $avatar;
			$avatar_reset = true;
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

			return Response::redirect('admin/profiles/edit/' . $id);
		}

		if($password_reset) {
			$input['password'] = Hash::make($input['password']);
		}

		User::update($id, $input);

		Notify::success(__('users.updated'));

		return Response::redirect('admin/profiles/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/profiles/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

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

	Route::post('admin/profiles/add', function() {
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

			return Response::redirect('admin/profiles/add');
		}

		$input['password'] = Hash::make($input['password']);

		User::create($input);

		Notify::success(__('users.created'));

		return Response::redirect('admin/profiles');
	});

	/*
		Delete user
	*/
	Route::get('admin/profiles/delete/(:num)', function($id) {
		$self = Auth::user();

		if($self->id == $id) {
			Notify::danger(__('users.delete_error'));

			return Response::redirect('admin/profiles/edit/' . $id);
		}

		User::where('id', '=', $id)->delete();

		Notify::success(__('users.deleted'));

		return Response::redirect('admin/profiles');
	});

});
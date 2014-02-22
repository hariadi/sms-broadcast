<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/dashboard', 'admin/dashboard/(:num)'), function($page = 1) {
		
		$id = Auth::user()->id;
		$vars['messages'] = Notify::read();
		$vars['client'] = Dashboard::view($id);
		$vars['broadcasts'] = Broadcast::paginate($page, Config::get('meta.posts_per_page'));

		$uuid = $vars['client']->credit;

		$credit_avail = User::where('id', '=', $id)->column(array('credit'));
		$credit_use = Broadcast::where('client', '=', $id)->sum('credit');

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use
		);
		
		$vars['fields'] = Extend::fields('user', Auth::user()->id);

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'client' => __('global.client')
		);

		return View::create('dashboard/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


});
<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/dashboard', 'admin/dashboard/(:num)'), function($page = 1) {
		
		$userid = Auth::user()->id;
		$vars['messages'] = Notify::read();
		$vars['client'] = Dashboard::view($userid);
		$vars['transactions'] = Transaction::paginate($page, Config::get('meta.posts_per_page'));

		$uuid = $vars['client']->credit;

		$credit_avail = Credit::where('client', '=', $userid)->where('uuid', '=', $uuid)->column(array('credit'));
		$credit_use = Transaction::where('client', '=', $userid)->where('guid', '=', $uuid)->sum('credit');

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use,
			'balance' => $credit_avail + $credit_use
		);
		

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
<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/dashboard', 'admin/dashboard/(:num)'), function($page = 1) {
		
		$userid = Auth::user()->id;
		$vars['messages'] = Notify::read();
		$vars['client'] = Dashboard::view($userid);

		$perpage = Config::meta('posts_per_page');
		$total = Transaction::count();
		$transactions = Transaction::where('client', '=', $userid)->sort('created', 'desc')->take($perpage)->skip(($page - 1) * $perpage)->get();

		$pagination = new Paginator($transactions, $total, $page, $perpage, Uri::to('admin/dashboard'));

		$vars['transactions'] = $pagination;

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'user' => __('global.user')
		);

		return View::create('dashboard/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


});
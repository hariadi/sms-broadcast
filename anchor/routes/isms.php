<?php

Route::collection(array('before' => 'auth,admin,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/isms', 'admin/isms/(:num)'), function($page = 1) {

		$vars['token'] = Csrf::token();
		$vars['messages'] = Notify::read();
		$vars['reports'] = Isms::paginate($page, Config::get('meta.posts_per_page'));

		return View::create('isms/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Sync report with ISMS
	*/
	Route::get(array('admin/isms/sync'), function($page = 1) {

		$isms = Isms::update();
		Notify::success(__('report.uptodate'));

		return Response::redirect('admin/isms');
	});


});
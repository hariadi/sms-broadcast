<?php

Route::collection(array('before' => 'auth,admin,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/reports', 'admin/reports/(:num)'), function($page = 1) {

		$vars['token'] = Csrf::token();
		$vars['messages'] = Notify::read();
		$vars['reports'] = Report::paginate($page, Config::get('meta.posts_per_page'));

		return View::create('reports/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Sync report with ISMS
	*/
	Route::get(array('admin/reports/sync'), function($page = 1) {

		$reports = Report::update();
		Notify::success(__('report.uptodate'));

		return Response::redirect('admin/reports');
	});


});
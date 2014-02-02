<?php

Route::collection(array('before' => 'auth,admin,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get(array('admin/reports', 'admin/reports/(:num)'), function($page = 1) {

		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['reports'] = Broadcast::paginate($page, Config::get('meta.posts_per_page'), Uri::to('admin/reports'));
		$vars['status'] = 'all';

		$vars['sorts'] = array(
			'client' => __('global.client'),
			'messages' => __('global.messages'),
			'sender' => __('global.sender'),
			'date' => __('global.date'),
			'status' => __('global.status'),
		);

		$vars['types'] = array(
			'month' => __('reports.month'),
			'week' => __('reports.week'),
			'day' => __('reports.day')
		);

		$vars['orders'] = array(
			'month' => __('reports.asc'),
			'week' => __('reports.desc')
		);

		return View::create('reports/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::get(array(
		'admin/reports/interval', 
		'admin/reports/interval/(:any)',
		'admin/reports/interval/(:any)/(:num)'),

	function($interval = 'month', $page = 1) {

		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$filter = array('use' => 'interval', 'interval' => $interval);
		$vars['reports'] = Broadcast::search($filter, $page, Config::get('meta.posts_per_page'), Uri::to('admin/reports'));
		print_r($vars['reports']);
		exit();
		$vars['status'] = 'all';

		$vars['sorts'] = array(
			'client' => __('global.client'),
			'messages' => __('global.messages'),
			'sender' => __('global.sender'),
			'date' => __('global.date'),
			'status' => __('global.status'),
		);

		$vars['types'] = array(
			'month' => __('reports.month'),
			'week' => __('reports.week'),
			'day' => __('reports.day')
		);

		$vars['orders'] = array(
			'month' => __('reports.asc'),
			'week' => __('reports.desc')
		);

		return View::create('reports/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/reports', function() {
		$input = Input::get(array('use', 'interval', 'from_date', 'to_date', 'sort'));

		if(!empty($input['from_date'])) {
			$input['from_date'] = Date::mysql($input['from_date']);
		}

		if(!empty($input['to_date'])) {
			$input['to_date'] = Date::mysql($input['to_date']);
		}

		if(empty($input['interval'])) {
			$input['interval'] = 'month';
		}

		if(empty($input['sort'])) {
			$input['sort'] = 'ID';
		}

		print_r($input);
		exit();

		return Response::redirect('admin/reports');
	});

	Route::get('admin/reports/export/(:any)', function($type) {

		return Response::redirect('admin/reports');
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
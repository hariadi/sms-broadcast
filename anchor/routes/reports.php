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
		$object = new stdClass();
		$filter = array(
			'use' => 'interval',
			'client' => '',
			'from' => '', 
			'to' => '',
			'type' => '', 
			'sort' => '', 
			'order' => '',
			'type' => '', 
		);
		$vars['search'] = json_decode(json_encode($filter), FALSE);

		$vars['sorts'] = array(
			'client' => __('global.client'),
			'messages' => __('global.messages'),
			'sender' => __('global.sender'),
			'created' => __('global.created'),
			'status' => __('global.status'),
		);

		$vars['types'] = array(
			'month' => __('reports.month'),
			'week' => __('reports.week'),
			'day' => __('reports.day')
		);

		$vars['orders'] = array(
			'asc' => __('reports.asc'),
			'desc' => __('reports.desc')
		);

		return View::create('reports/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


	/*
		Search Date Range
	*/
	Route::get(array('admin/reports/search/daterange/(:any)/(:any)/(:any)/(:any)', 'admin/reports/search/daterange/(:any)/(:any)/(:any)/(:any)/(:num)'), function($from = null, $to = null, $sort = 'id', $order = 'desc', $page = 1) {

		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$from = Date::mysql($from);
		$to = Date::mysql($to);
		$filter = array(
			'use' => 'daterange', 
			'from' => $from, 
			'to' => $to, 
			'sort' => $sort, 
			'order' => $order,
			'type' => '', 
		);
		$vars['search'] = json_decode(json_encode($filter), FALSE);

		$vars['reports'] = Broadcast::search($filter, $page+1, Config::get('meta.posts_per_page'), Uri::to('admin/reports/search/daterange/' . $from . '/' . $to . '/' . $sort . '/' . $order));
		$vars['status'] = 'all';

		$vars['sorts'] = array(
			'client' => __('global.client'),
			'messages' => __('global.messages'),
			'sender' => __('global.sender'),
			'created' => __('global.created'),
			'status' => __('global.status'),
		);

		$vars['types'] = array(
			'month' => __('reports.month'),
			'week' => __('reports.week'),
			'day' => __('reports.day')
		);

		$vars['orders'] = array(
			'asc' => __('reports.asc'),
			'desc' => __('reports.desc')
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
		
		$vars['status'] = 'all';

		$vars['sorts'] = array(
			'client' => __('global.client'),
			'messages' => __('global.messages'),
			'sender' => __('global.sender'),
			'created' => __('global.created'),
			'status' => __('global.status'),
		);

		$vars['types'] = array(
			'month' => __('reports.month'),
			'week' => __('reports.week'),
			'day' => __('reports.day')
		);

		$vars['orders'] = array(
			'asc' => __('reports.asc'),
			'desc' => __('reports.desc')
		);

		return View::create('reports/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post(array('admin/reports/search', 'admin/reports/search/(:num)'), function($page = 1) {
		$input = Input::get(array('use', 'interval', 'from_date', 'to_date', 'sort', 'order'));

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

		if(empty($input['order'])) {
			$input['order'] = 'DESC';
		}

		if ($input['use'] == 'interval') {
			
			$uri = $input['use'] . '/' . $input['interval'] . '/' . $input['sort'] . '/' . $input['order'];
		} elseif ($input['use'] == 'daterange') {

			$uri = $input['use'] . '/' . $input['from_date'] . '/' . $input['to_date'] . '/' . $input['sort'] . '/' . $input['order'];

		}

		return Response::redirect('admin/reports/search/' . $uri  );
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
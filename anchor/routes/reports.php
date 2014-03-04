<?php

Route::collection(array('before' => 'auth,csrf'), function() {

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
	Route::get(array(
		'admin/reports/search/(:any)/(:any)',
		'admin/reports/search/(:any)/(:any)/(:num)'), 
	function($from = null, $to = null, $page = 1) {

		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		$filter = array(
			'from' => $from, 
			'to' => $to,
		);

		$from = Date::format($from, 'Y-m-d');
		$to = Date::format($to, 'Y-m-d');

		$vars['reports'] = Broadcast::search($filter, $page+1, Config::get('meta.posts_per_page'), Uri::to('admin/reports/search/' . $from . '/' . $to));

		$vars['search'] = json_decode(json_encode($filter), FALSE);
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
		$input = Input::get(array('from_date', 'to_date'));

		if (empty($input['from_date'])) {
			Notify::add('error', 'Please provide From Date');
			return Response::redirect('admin/reports');
		}

		if (empty($input['from_date'])) {
			Notify::add('error', 'Please provide To Date');
			return Response::redirect('admin/reports');
		}


		if(!empty($input['from_date'])) {
			$input['from_date'] = Date::format($input['from_date'], 'Y-m-d');
		}

		if(!empty($input['to_date'])) {
			$input['to_date'] = Date::format($input['to_date'], 'Y-m-d');
		}

		return Response::redirect('admin/reports/search/' . $input['from_date'] . '/' . $input['to_date']  );
	});

	/**
	 * Export to Excel
	 */
	Route::get(array('admin/reports/(:any)', 'admin/reports/search/(:any)/(:any)/(:any)', 'admin/reports/search/(:any)/(:any)/(:num)/(:any)'), function($from = null, $to = null, $type = 'xls') {

		$search = true;

		if (in_array($from, array('xls', 'pdf'))) {
			$type = $from;
			$search = false;
		}

		$query = Broadcast::sort('created', 'desc');
		if (Auth::user()->role != 'administrator') {
			$query = $query->where(Base::table('broadcasts.client'), '=', Auth::user()->id)
			->or_where(Base::table('broadcasts.account'), '=', Auth::user()->id)
			->where(Base::table('broadcasts.client'), '!=', Base::table('broadcasts.account'));
		}

		if ($search) {

			$from = Date::format($from, 'Y-m-d') . '00:00:00';
			$to = Date::format($to, 'Y-m-d') . '00:00:00';

			$query = $query->where(Base::table('broadcasts.created'), '>=', $from)->where(Base::table('broadcasts.created'), '<=', $to);
		}

		$reports = $query->get();

		require PATH . 'vendor/PHPExcel/PHPExcel' . EXT;

		$excel = new PHPExcel();

		// Set document properties
		$excel->getProperties()->setCreator("Transrec")
							 ->setLastModifiedBy("Transrec")
							 ->setTitle("Broadcast Report")
							 ->setSubject("Broadcast Report")
							 ->setDescription("Broadcast Report");

		$excel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Client')
          ->setCellValue('B1', 'Sender')
          ->setCellValue('C1', 'Recipient')
          ->setCellValue('D1', 'Created')
          ->setCellValue('E1', 'Keyword')
          ->setCellValue('F1', 'Message')
          ->setCellValue('G1', 'Status');

		foreach($reports as $key => $report) {
			$cell = (String) $key+2;
			//$recipients = implode(", ", Json::decode($report->recipient));
			$excel->setActiveSheetIndex(0)
			->setCellValue('A' . $cell, User::find($report->account)->real_name)
			->setCellValue('B' . $cell, User::find($report->client)->real_name)
			->setCellValue('C' . $cell, $report->recipient)
			->setCellValue('D' . $cell, $report->created)
			->setCellValue('E' . $cell, $report->keyword)
			->setCellValue('F' . $cell, $report->message)
			->setCellValue('G' . $cell, $report->status);
		}

		$excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

		// define report storage
		$storage = PATH . 'content/report' . DS;
		if(!is_dir($storage)) mkdir($storage);

		// before save, delete old report
		foreach (glob($storage . "*.{xls,xlsx,pdf}" ,GLOB_BRACE) as $file) {
			if(is_file($file))
		  unlink($file);
		}

		// report name
		$filename = 'reports_' . date('Y-m-d') . '.xls';
		$filepath = $storage . $filename;

		// save to report storage
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save($filepath);

		return Response::redirect('/content/report/' . $filename);

	});

});
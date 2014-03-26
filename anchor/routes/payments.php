<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List users
	*/
	Route::get(array('admin/payments', 'admin/payments/(:num)'), function($page = 1) {
		$id = Auth::user()->id;

		$vars['messages'] = Notify::read();
		$vars['user'] = User::find($id);

		$vars['profiles'] = User::paginate($page, Config::get('meta.posts_per_page'), Uri::to('admin/payments'));

		$credit = Topup::where('client', '=', $id)->sort('created', 'desc')->take(1)->column(array('credit'));

		$vars['profiles']->topup = $credit;

		$credit_avail = User::where('id', '=', $id)->column(array('credit'));
		$credit_use = Broadcast::where('account', '=', $id)->sum('credit');
		
		//$credit_expired =

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use
		);

		$vars['fields'] = Extend::fields('user', $id);



		return View::create('payments/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/**
	 * Export to Excel
	 */
	//Route::get(array('admin/payments/(:any)', 'admin/payments/search/(:any)/(:any)/(:any)', 'admin/payments/search/(:any)/(:any)/(:num)/(:any)'), function($from = null, $to = null, $type = 'xls') {
	Route::get(array('admin/payments/xls', 'admin/payments/view/(:num)/xls'), function($single = null) {
	//Route::get('admin/payments/xls', function() {
		$id = $single ? $single : Auth::user()->id;

		$query = User::sort('id', 'asc');

		if (Auth::user()->role != 'administrator' or $single) {
			$query = $query->where(Base::table('users.id'), '=', $id);
		}
		$profiles = $query->get();

		$credit = Topup::where('client', '=', $id)->sort('created', 'desc')->take(1)->column(array('credit'));

		$total_credit = 0; 
        $total_use = 0;
        $total_expired = 0;
        $total_balance = 0;

		require PATH . 'vendor/PHPExcel/PHPExcel' . EXT;

		$excel = new PHPExcel();

		// Set document properties
		$excel->getProperties()->setCreator("Transrec")
							 ->setLastModifiedBy("Transrec")
							 ->setTitle("Client Report")
							 ->setSubject("Client Report")
							 ->setDescription("Client Report");

		$excel->setActiveSheetIndex(0)
          ->setCellValue('A1', 'Client')
          ->setCellValue('B1', 'Purchase Date')
          ->setCellValue('C1', 'Expired Date')
          ->setCellValue('D1', 'Charge')
          ->setCellValue('E1', 'Credit')
          ->setCellValue('F1', 'Used')
          ->setCellValue('G1', 'Expired')
          ->setCellValue('H1', 'Balance');

        $excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

		foreach($profiles as $key => $profile) {

			$use = Broadcast::where('account', '=', $profile->id)->sum('credit');
			$profile->topup = Topup::where('client', '=', $profile->id)->sort('created', 'desc')->take(1)->column(array('credit'));
			$profile->expired = Topup::where('client', '=', $profile->id)->sort('created', 'desc')->take(1)->column(array('expired'));
			
			$balance = money($profile->topup - $use);
			$total_credit += $profile->topup; 
            $total_use += $use;
            $total_expired += $profile->expire;
            $total_balance += $balance;
            $topup = $profile->topup ? $profile->topup : money(0);
            $expire = $profile->expire ? $profile->expire : money(0);

			$cell = (String) $key+2;
			$total = (String) $key+3;
			$key++;
			//$recipients = implode(", ", Json::decode($profile->recipient));
			$excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cell, $profile->real_name)
	          ->setCellValue('B' . $cell, Date::format($profile->created))
	          ->setCellValue('C' . $cell, Date::format($profile->expired))
	          ->setCellValue('D' . $cell, $profile->charge)
	          ->setCellValue('E' . $cell, $topup)
	          ->setCellValue('F' . $cell, $use)
	          ->setCellValue('G' . $cell, $expire)
	          ->setCellValue('H' . $cell, $balance)

	          ->setCellValue('A' . (String) $total, 'TOTAL')
	          ->setCellValue('E' . (String) $total, '=SUM(E2:E'.($total-1).')')
	          ->setCellValue('F' . (String) $total, '=SUM(F2:F'.($total-1).')')
	          ->setCellValue('G' . (String) $total, '=SUM(G2:G'.($total-1).')')
	          ->setCellValue('H' . (String) $total, '=SUM(H2:H'.($total-1).')');

		}
		$excel->getActiveSheet()->getStyle('A' . (String) $total .':H' . (String) $total)->getFont()->setBold(true);

		// define report storage
		$storage = PATH . 'content/report' . DS;
		if(!is_dir($storage)) mkdir($storage);

		// before save, delete old report
		foreach (glob($storage . "*.{xls,xlsx,pdf}" ,GLOB_BRACE) as $file) {
			if(is_file($file))
		  unlink($file);
		}

		// report name
		$filename = 'profiles_' . date('Y-m-d') . '.xls';
		$filepath = $storage . $filename;

		// save to report storage
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save($filepath);

		return Response::redirect('/content/report/' . $filename);

	});

});
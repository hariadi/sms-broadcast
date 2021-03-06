<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List users
	*/
	Route::get(array('admin/profiles', 'admin/profiles/(:num)'), function($page = 1) {
		$id = Auth::user()->id;

		$vars['messages'] = Notify::read();
		$vars['user'] = User::find($id);
		$credit_avail = User::where('id', '=', $id)->column(array('credit'));
		$credit_use = Broadcast::where('account', '=', $id)->sum('credit');
		
		//$credit_expired =

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use
		);

		$vars['fields'] = Extend::fields('user', $id);



		return View::create('profiles/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Broadcast
	*/
	Route::get('admin/profiles/view/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['user'] = User::find($id);

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use
		);
		$vars['fields'] = Extend::fields('user', $id);

		return View::create('profiles/view', $vars)
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

		$credit = Topup::where('client', '=', $id)->sort('created', 'desc')->take(1)->column(array('credit'));

		$credit_avail = User::where('id', '=', $id)->column(array('credit'));
		$credit_use = Broadcast::where('client', '=', $id)->sum('credit');

		$vars['credits'] = array(
			'available' => $credit_avail ? $credit_avail : 0,
			'used' => $credit_use
		);

		$vars['fields'] = Extend::fields('user', $id);

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'client' => __('global.client')
		);

		$vars['fields'] = Extend::fields('user', $id);

		return View::create('profiles/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/profiles/edit', function() {
		$id = Auth::user()->id;
		$input = Input::get(array('username', 'email', 'real_name', 'bio'));
		$password_reset = false;
		$avatar_reset = false;

		if($password = Input::get('password')) {
			$input['password'] = $password;
			$password_reset = true;
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
		Extend::process('user', $id);

		Notify::success(__('users.updated'));

		return Response::redirect('admin/profiles/edit/');
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
			'client' => __('global.client')
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

	/**
	 * Export to Excel
	 */
	//Route::get(array('admin/profiles/(:any)', 'admin/profiles/search/(:any)/(:any)/(:any)', 'admin/profiles/search/(:any)/(:any)/(:num)/(:any)'), function($from = null, $to = null, $type = 'xls') {
	Route::get(array('admin/profiles/xls', 'admin/profiles/view/(:num)/xls'), function($single = null) {
	//Route::get('admin/profiles/xls', function() {
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
          ->setCellValue('D1', 'Credit')          
          ->setCellValue('E1', 'Used')
          ->setCellValue('F1', 'Expired')
          ->setCellValue('G1', 'Balance');

        $excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

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
	          ->setCellValue('D' . $cell, $topup)	          
	          ->setCellValue('E' . $cell, $use)
	          ->setCellValue('F' . $cell, $expire)
	          ->setCellValue('G' . $cell, $balance)

	          ->setCellValue('A' . (String) $total, 'TOTAL')
	          ->setCellValue('D' . (String) $total, '=SUM(D2:D'.($total-1).')')
	          ->setCellValue('E' . (String) $total, '=SUM(E2:E'.($total-1).')')
	          ->setCellValue('F' . (String) $total, '=SUM(F2:F'.($total-1).')')
	          ->setCellValue('G' . (String) $total, '=SUM(G2:G'.($total-1).')');

		}
		$excel->getActiveSheet()->getStyle('A' . (String) $total .':G' . (String) $total)->getFont()->setBold(true);

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
<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Menu Items
	*/
	Route::get('admin/dashboard', function() {
		$vars['messages'] = Notify::read();
		$vars['pages'] = Page::where('show_in_menu', '=', 1)->sort('menu_order')->get();

		return View::create('dashboard/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


});
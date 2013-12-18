<?php

Route::collection(array('before' => 'auth,csrf'), function() {

	/*
		List Broadcasts
	*/
	Route::get(array('admin/broadcasts', 'admin/broadcasts/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['broadcasts'] = Broadcast::paginate($page, Config::get('meta.posts_per_page'));
		$vars['status'] = 'all';

		return View::create('broadcasts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		List broadcasts by status and paginate through them
	*/
	Route::get(array(
		'admin/broadcasts/status/(:any)',
		'admin/broadcasts/status/(:any)/(:num)'), function($status, $page = 1) {

		$query = Broadcast::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('broadcasts.client'));

		$total = $query->count();
		
		$query = $query->where(Base::table('broadcasts.status'), '=', $status);

		$per_page = Config::meta('posts_per_page');
		
		$broadcasts = $query->sort('date')
			->take($per_page)
			->skip(--$page * $per_page)
			->get(array(Base::table('broadcasts.*'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));

		$url = Uri::to('admin/broadcasts/status');

		$pagination = new Paginator($broadcasts, $total, $page, $per_page, $url);

		$vars['messages'] = Notify::read();
		$vars['broadcasts'] = $pagination;
		$vars['status'] = $status;

		return View::create('broadcasts/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit Broadcast
	*/
	Route::get('admin/broadcasts/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['broadcast'] = Broadcast::get('id', $id);

		return View::create('broadcasts/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/broadcasts/edit/(:num)', function($id) {
		$input = Input::get(array('title', 'slug', 'description'));

		$validator = new Validator($input);

		$validator->check('title')
			->is_max(3, __('broadcasts.title_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/broadcasts/edit/' . $id);
		}

		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		$input['slug'] = slug($input['slug']);

		Broadcast::update($id, $input);

		Notify::success(__('broadcasts.updated'));

		return Response::redirect('admin/broadcasts/edit/' . $id);
	});

	/*
		Add Broadcast
	*/
	Route::get('admin/broadcasts/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		return View::create('broadcasts/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/broadcasts/add', function() {
		$input = Input::get(array('title', 'slug', 'description'));

		$validator = new Validator($input);

		$validator->check('title')
			->is_max(3, __('broadcasts.title_missing'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/broadcasts/add');
		}

		if(empty($input['slug'])) {
			$input['slug'] = $input['title'];
		}

		$input['slug'] = slug($input['slug']);

		Broadcast::create($input);

		Notify::success(__('broadcasts.created'));

		return Response::redirect('admin/broadcasts');
	});

	/*
		Delete Broadcast
	*/
	Route::get('admin/broadcasts/delete/(:num)', function($id) {
		$total = Broadcast::count();

		if($total == 1) {
			Notify::error(__('broadcasts.delete_error'));

			return Response::redirect('admin/broadcasts/edit/' . $id);
		}

		// move posts
		$broadcast = Broadcast::where('id', '<>', $id)->fetch();

		// delete selected
		Broadcast::find($id)->delete();

		// update posts
		Post::where('broadcast', '=', $id)->update(array(
			'broadcast' => $broadcast->id
		));

		Notify::success(__('broadcasts.deleted'));

		return Response::redirect('admin/broadcasts');
	});

});

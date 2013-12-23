<?php

class Broadcast extends Base {

	public static $table = 'broadcasts';

	public static function dropdown() {
		$items = array();

		foreach(static::get() as $item) {
			$items[$item->id] = $item->title;
		}

		return $items;
	}

	public static function recipient($recipient) {
		return static::where('recipient', 'like', $recipient)->fetch();
	}

	public static function get($row, $val) {
		return static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('broadcasts.client'))
			->where(Base::table('broadcasts.'.$row), '=', $val)
			->fetch(array(Base::table('broadcasts.*'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));
	}

	public static function paginate($page = 1, $per_page = 10) {

		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('broadcasts.client'));
		
		if (Auth::user()->role != 'administrator') {
			$query = $query->where(Base::table('broadcasts.client'), '=', Auth::user()->id);
		}

		$count = $query->count();

		$broadcasts = $query->take($per_page)
			->skip(($page - 1) * $per_page)
			->get(array(Base::table('broadcasts.*'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));

		//$results = $query->take($per_page)->skip(($page - 1) * $per_page)->sort('date')->get();

		return new Paginator($broadcasts, $count, $page, $per_page, Uri::to('admin/broadcasts'));
	}

}
<?php

class Transaction extends Base {

	public static $table = 'transactions';

	public static function id($id) {
		return static::get('id', $id);
	}

	private static function get($row, $val) {
		return static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('transactions.client'))
			->where(Base::table('transactions.'.$row), '=', $val)
			->fetch(array(Base::table('transactions.*'),
				Base::table('users.bio as author_bio'),
				Base::table('users.real_name as author_name')));
	}

	public static function paginate($page = 1, $perpage = 10) {

		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('transactions.client'));

		$count = $query->count();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->sort(Base::table('transactions.created'), 'desc')
			->get(array(Base::table('transactions.*'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));
			
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/dashboard'));
	}

}
<?php

class Dashboard extends Base {

	public static $table = 'users';

	public static function view($id) {

		$query = static::left_join(Base::table('credits'), Base::table('users.id'), '=', Base::table('credits.client'))
			->where(Base::table('users.id'), '=', $id)
			//->where(Base::table('users.credit'), '=', Base::table('credits.guid'))
			->fetch(array(Base::table('users.*'),
				Base::table('credits.guid as client_guid'),
				Base::table('credits.credit as client_credit'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));

			return $query;

		//	->where(Base::table('credits.status'), '=', 'published');

	}

	public static function transactions($id, $page = 1, $perpage = 10) {

		$query = static::left_join(Base::table('transactions'), Base::table('users.id'), '=', Base::table('transactions.client'))
			->where(Base::table('users.id'), '=', $id);

		$count = $query->count();

		$query = $query->fetch(array(Base::table('transactions.*'),
				Base::table('users.id as client_id'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('created', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('dashboard'));

	}

	public static function search($params = array()) {
		$query = static::where('status', '=', 'active');

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('dashboard'));
	}

}
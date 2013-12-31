<?php

class Credit extends Base {

	public static $table = 'credits';


	public static function paginate($page = 1, $perpage = 10) {

		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('credits.client'));

		//$query->where(Base::table('users.credit'), '=', Base::table('credits.uuid'));

		$count = $query->count();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->sort(Base::table('credits.created'), 'desc')
			->get(array(Base::table('credits.*'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));
			
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/dashboard'));
	}

}
<?php

class Topup extends Base {

	public static $table = 'topups';


	public static function paginate($page = 1, $perpage = 10) {

		$query = static::left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('topups.client'));

		//$query->where(Base::table('users.credit'), '=', Base::table('topups.uuid'));

		$count = $query->count();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->sort(Base::table('topups.created'), 'desc')
			->get(array(Base::table('topups.*'),
				Base::table('users.bio as client_bio'),
				Base::table('users.real_name as client_name')));
			
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/dashboard'));
	}

}
<?php

class User extends Base {

	public static $table = 'users';

	public static function search($params = array()) {
		$query = static::where('status', '=', 'active');

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}

	public static function paginate($page = 1, $perpage = 10, $url = null, $single = false) {
	
		$url = $url ? $url : Uri::to('users');

		$query = static::left_join(Base::table('topups'), Base::table('topups.client'), '=', Base::table('users.id'));

		if ($single) {
			$query = $query->where(Base::table('users.id'), '=',  Auth::user()->id);
		}

		$count = $query->count();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->group(Base::table('users.id'))
			->get(array(Base::table('users.*'),
				Base::table('topups.credit as topup'),
				Base::table('topups.expired as expired'),
				Base::table('topups.expire as expire')));

		return new Paginator($results, $count, $page, $perpage, $url);
	}
/*
	public static function paginate($page = 1, $perpage = 10, $profiles = false) {

		$query = static::multi_left_join(
			Base::table('credits'),
			array(
				array(Base::table('credits.client'), '=', Base::table('users.id')),
				array(Base::table('credits.uuid'), '=', Base::table('users.credit'))
			)
		);

		$count = $query->count();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->get(array(Base::table('users.*'),
				Base::table('credits.credit as balance')));
			
		return new Paginator($results, $count, $page, $perpage, Uri::to('users'));
	}
*/

	public static function upload($file) {
		$storage = PATH . 'content' . DS . 'avatar'. DS;

		if(!is_dir($storage)) mkdir($storage);

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		// Added rtrim to remove file extension before adding again
		$filename = slug(rtrim($file['name'], '.' . $ext)) . '.' . $ext;
		$filepath = $storage . $filename;

		if(move_uploaded_file($file['tmp_name'], $filepath)) {
			return $filepath;
		}

		return false;
	}

}
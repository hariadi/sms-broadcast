<?php

class Schedule extends Base {

	public static $table = 'schedules';

	public static function view($id) {

		$query = static::left_join(Base::table('broadcasts'), Base::table('broadcasts.id'), '=', Base::table('schedules.broadcast'))
			->where(Base::table('schedules.id'), '=', $id)
			->fetch(array(Base::table('schedules.*'),
				Base::table('broadcasts.client as client'),
				Base::table('broadcasts.sender as sender'),
				Base::table('broadcasts.recipient as recipient'),
				Base::table('broadcasts.keyword as keyword'),
				Base::table('broadcasts.created as created'),
				Base::table('broadcasts.message as message')));

			return $query;

	}

	public static function paginate($page = 1, $perpage = 10) {

		$query = static::left_join(Base::table('broadcasts'), Base::table('broadcasts.id'), '=', Base::table('schedules.broadcast'));

		if ($id = Auth::user()->id) {
			$query = $query->where(Base::table('broadcasts.client'), '=', $id);
		}

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('schedule')->get();

		$results = $query->take($perpage)
			->skip(($page - 1) * $perpage)
			->sort(Base::table('broadcasts.created'), 'desc')
			->get(array(Base::table('schedules.*'),
				Base::table('broadcasts.client as client'),
				Base::table('broadcasts.sender as sender'),
				Base::table('broadcasts.recipient as recipient'),
				Base::table('broadcasts.keyword as keyword'),
				Base::table('broadcasts.created as created'),
				Base::table('broadcasts.message as message')));

		return new Paginator($results, $count, $page, $perpage, Uri::to('schedules'));
	}

}
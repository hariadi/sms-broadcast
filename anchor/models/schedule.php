<?php

class Schedule extends Base {

	public static $table = 'schedules';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('schedule')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('schedules'));
	}

}
<?php

/**
 * Theme functions for broadcasts
 */

function total_broadcasts() {
	if( ! $broadcasts = Registry::get('broadcasts')) {
		$broadcasts = Category::get();

		$broadcasts = new Items($broadcasts);

		Registry::set('broadcasts', $broadcasts);
	}

	return $broadcasts->length();
}

// loop broadcasts
function broadcasts() {
	if( ! total_broadcasts()) return false;

	$items = Registry::get('broadcasts');

	if($result = $items->valid()) {
		// register single broadcast
		Registry::set('broadcast', $items->current());

		// move to next
		$items->next();
	}

	return $result;
}

// single broadcasts
function broadcast_id() {
	return Registry::prop('broadcast', 'id');
}

function broadcast_title() {
	return Registry::prop('broadcast', 'title');
}

function broadcast_slug() {
	return Registry::prop('broadcast', 'slug');
}

function broadcast_description() {
	return Registry::prop('broadcast', 'description');
}

function broadcast_url() {
	return base_url('broadcast/' . broadcast_slug());
}

function broadcast_count() {
	return Query::table(Base::table('posts'))
		->where('broadcast', '=', broadcast_id())
		->where('status', '=', 'published')->count();
}
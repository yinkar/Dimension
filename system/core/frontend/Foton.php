<?php

class Foton {
	public static function use() {
		echo '<link rel="stylesheet" type="text/css" href="'.site_url('public/foton/foton.css').'" />'."\n";
	}

	public static function get() {
		return site_url('public/foton/foton.css');
	}
}
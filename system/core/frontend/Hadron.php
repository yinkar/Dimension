<?php

class Hadron {
	public static function use() {
		echo '<script type="text/javascript" src="'.site_url('public/hadron/hadron.js').'"></script>'."\n";
	}

	public static function get() {
		return site_url('public/hadron/hadron.js');
	}
}
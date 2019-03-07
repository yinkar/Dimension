<?php

$dirpaths = array(

	'system_dir' => 'system',
	'user_dir' => 'user'

);

require_once $dirpaths['user_dir'].'/config/config.php';

$config = array_merge($config, $dirpaths);

if(empty($config['site_url'])) {
	$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
	$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

	$config['site_url'] = $root;
}


function site_url($addition = '') {
	global $config;
	$site_url = rtrim($config['site_url'], '/').'/';

	if(!empty($addition)) {
		$site_url .= $addition;
	}

	return $site_url;
}


/* Başlangıç dosyasını sayfaya ekle */
require_once $config['system_dir'].'/init.php';

require_once $config['user_dir'].'/config/web.php';

/* Başlangıç dosyası içinde çağırılan Application class'ını kullanarak bir nesne oluştur ve MVC altyapısını çalışmaya hazır hale getir */
$app = new Application($config);
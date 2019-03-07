<?php

class Controller {
	protected function model($model) {
		global $config;
		/* Gönderilen model ismini taşıyan dosyayı sayfaya ekle ve bir model nesnesi oluştur */
		require_once $config['user_dir'].'/models/'.$model.'.php';
		return new $model();
	}

	protected function view($view, $data = array()) {
		/* Bir template objesi oluştur ve view fonksiyonu çağrıldığında gönderilen data dizisini template ile ilişkilendir, sonrasında seçilen view sayfasını render fonksiyonuyla çalıştır */
		$template = new Template;

		foreach ($data as $key => $value) {
			$template->assign($key, $value);
		}

		$template->render($view, $data);
	}
}
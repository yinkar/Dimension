<?php

class Application {
	/* Varsayılan controller'ların, methodların ve parametrelerin biriktirileceği dizinin tanımlanması */
	protected $_controller;
	protected $_method;
	protected $_parameters = array();

	/* Hataları biriktirecek olan dizi değişkeni */
	protected $_errors = array();

	/* Application nesnesi oluşturulduğu an çalışacak constructor fonksiyonu içinde config dosyasında belirlenmiş olan, projenin geliştirilme sürecinde mi olduğu veya yayında mı olduğu kontrolü */
	public function __construct($config) {

		switch ($config['mode']) {
			case 'development':
				error_reporting(-1);
				break;
			
			default:
				error_reporting(0);
				break;
		}

		/* Config dosyasında belirlenmiş olan varsayılan controller ve method'un atanması */
		$this->_controller = $config['default_controller'];
		$this->_method = $config['default_method'];

		/* OOP yapılarını oluşturmadan önce düz PHP kodlarını barındıran, helpers klasöründeki tüm PHP dosyalarını sayfaya dahil et */
		foreach(glob($config['user_dir'].'/helpers/*.php') as $_helper) {
			require_once $_helper;
		}

		/* Libraries klasöründeki kullanıcının oluşturacağı tüm class dosyalarını sayfaya ekle */	
		foreach(glob($config['user_dir'].'/libraries/*.php') as $_helper) {
			require_once $_helper;
		}

		/* GET yoluyla gelen ve .htaccess dosyası ile gerçek gibi gösterilen sanal URL'i uygun koşullarda parçalayıp diziye dönüştür */
		$_url = $this->getURL();

		/* Controller adını taşıması gereken URL dizi değişkeninin ilk öğesi controllers klasöründe gerçek bir dosyaya işaret ediyor mu */
		if(file_exists($config['user_dir'].'/controllers/'.$_url[0].'.php')) {
			/* Koşul sağlanıyorsa nesnenin controller değerini URL değişkeninin ilk öğesine eşitle ve kalan değerlerin method kontrolünden de sonra direkt olarak bir parametre dizisi oluşturmasını sağlamak için dizinin ilk öğesini sil  */
			$this->_controller = $_url[0];
			unset($_url[0]);
		}
		

		/* Kontroller sonrasında controller ismini taşıyan dosyayı user/controller dizini içinden alarak dosyaya ekle ve bir nesne olarak oluşmasını sağla */
		require_once $config['user_dir'].'/controllers/'.$this->_controller.'.php';

		$this->_controller = new $this->_controller;


		/* URL dizi değişeni boşsa, diğer bir deyişle ilk parametresi hiç yoksa varsayılan controller'a işaret edecektir. Boş olmamasına rağmen bir Controller'a denk değilse bunun bir parametre olup olmadığı kontrolünü yap. Bunun için varsayılan controller'ın varsayılan method'unun parametre alıp almadığını kontrol et */
		if(!empty($_url[0])) {
			$_reflection_method = new ReflectionMethod($this->_controller, $this->_method);

			if(empty($_reflection_method->getParameters())) {
				$_errors[] = array('controller_error', $_url[0]);
			}
		}

		/* URL dizi değişkeninin ikinci öğesinin method adını işaret etmesi gerekiyor, olup olmadığını kontrol et */
		if(isset($_url[1])) {
			/* İkinci öğe mevcutsa controller nesnesi içinde bu isimde bir method olup olmadığını kontrol et ve varsa Application nesnesinin method değişkenini buna eşitleyerek yalnızca parametrelerin kalması için ikinci öğeyi de sil */
			if(method_exists($this->_controller, $_url[1])) {
				$this->_method = $_url[1];
				unset($_url[1]);
			}
		}

		/* Kalan URL dizisi öğelerini sıfırdan sıralayarak nesnenin parameters değişkenine aktar, URL dizisi boşsa parameters değişkenini boş bir diziye eşitle */
		$this->_parameters = $_url ? array_values($_url) : array();

		/* Oluşturulan controller nesnesinin verilen isimdeki metodunu parametreleri de göndererek çağır */
		call_user_func_array(array($this->_controller, $this->_method), $this->_parameters);

		/* Hata varsa, hata dizisi en az bir eleman içeriyorsa bunları hata belirteci ve isim parametrelerini göndererek showError fonksiyonundan geçirme yoluyla ekrana bas */
		if(!empty($_errors)) {
			foreach ($_errors as $_error_item) {
				echo $this->showError($_error_item[0], $_error_item[1]);
			}
		}
	}

	public function getURL() {
	/* .htaccess dosyası yardımıyla kullanıcıya "home/index/test" gibi bir şekilde gösterilen "?url=home/index/test" biçimindeki sanal URL'i ayıkla ve uygun yerlerden bölerek her bir parçası bir dizi elemanı olacak şekilde döndür */
		if(isset($_GET['url'])) {
			return explode('/', filter_var(trim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
		else if(isset($_SERVER['PATH_INFO'])) {
			return explode('/', filter_var(trim(ltrim($_SERVER['PATH_INFO'], 'index.php'), '/'), FILTER_SANITIZE_URL));
		}
	}

	public function showError($error, $data = '') {
	/* Gönderilen hata belirteci ve hataya neden olan değerin ismini uygun bir biçimde döndür */
		switch ($error) {
			case 'controller_error':
				return '<div style="color: #000000; font-weight: bold;">Controller doesn\'t exist: <span style="color: crimson;">'.$data.'</span></div>';
				break;
			default:
				return false;
				break;
		}
	}
}
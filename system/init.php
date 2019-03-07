<?php

/* Rotaların belirlenmesi için Routes class'ını sayfaya dahil et. Ana dizindeyse '/', ana dizinde değilse url isimli GET değişkeniyle gönderilen parametreyle bir Router objesi oluştur */
require_once 'core/Router.php';
new Router(isset($_GET['url']) ? $_GET['url'] : '/');

/* Application class'ını sayfaya ekleyerek temel çekirdeği oluşturan yapıları çağır */
require_once 'core/Dimension.php';

/* Template engine class'ını sayfaya ekle */
require_once 'core/Template.php';

/* Tüm controller'ların türetileceği controller class'ını sayfaya dahil et */
require_once 'core/Controller.php';

require_once 'core/frontend/Foton.php';
require_once 'core/frontend/Hadron.php';
<?php
ini_set("display_errors", E_ALL);
define('DS', DIRECTORY_SEPARATOR);
define('ROOTWEB', realpath(dirname(__FILE__)) . DS );
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('SYS_PATH', ROOT .'system' . DS);
define('CNT_PATH', ROOT .'controllers' . DS);
define('MOD_PATH', ROOT .'models' . DS);
define('VIE_PATH', ROOT .'views' . DS);
define('VEN_PATH', ROOT .'vendors' . DS);

try{
require_once SYS_PATH . 'Config.php';
require_once SYS_PATH . 'Request.php';
require_once SYS_PATH . 'Bootstrap.php';
require_once SYS_PATH . 'Controller.php';
require_once SYS_PATH . 'Model.php';
require_once SYS_PATH . 'View.php';
require_once SYS_PATH . 'Csrf.php';
require_once SYS_PATH . 'Session.php';

/* Include vendors */
require_once VEN_PATH.'Twig'.DS.'Autoloader.php';

//require_once CNT_PATH . 'Validaciones.php';

Session::init();

Bootstrap::run(new Request);
}
catch(Exception $e){
    echo $e->getMessage();
}
?>

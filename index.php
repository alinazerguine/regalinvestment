<?php
error_reporting(1);
ini_set("display_errors","on");

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;


/*date_default_timezone_set("America/Los_Angeles");*/
chdir(dirname(__DIR__));

defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__). ''));

require_once "vendor/constants.php";
require_once "vendor/functions.php";
require_once "vendor/public_access.php";
require_once "vendor/site_assets.php";
require_once "vendor/recaptcha/simple-php-captcha.php";

/*require_once 'vendor/PayPal/paypal/autoload.php';
require_once 'vendor/PayPal/common/user.php';
require_once 'vendor/PayPal/common/order.php';
require_once 'vendor/PayPal/common/paypal.php';
require_once 'vendor/PayPal/common/util.php';*/

// Decline static file requests back to the PHP built-in webserver
if(php_sapi_name() === 'cli-server') {
	$path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
	if (__FILE__ !== $path && is_file($path)) {
		return false;
	}
	unset($path);
}


// Composer autoloading
include ROOT_PATH. '/vendor/autoload.php';

if (! class_exists(Application::class)) {
	throw new RuntimeException(
		"Unable to load application.\n"
		. "- Type `composer install` if you are developing locally.\n"
		. "- Type `vagrant ssh -c 'composer install'` if you are using Vagrant.\n"
		. "- Type `docker-compose run zf composer install` if you are using Docker.\n"
	);
}

// Retrieve configuration
$appConfig = require ROOT_PATH.'/config/application.config.php';
if (file_exists(__DIR__ . '/../config/development.config.php')) {
	$appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/../config/development.config.php');
}

// Run the application!
Application::init($appConfig)->run();
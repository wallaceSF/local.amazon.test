<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

/**
 * ----------------------------------------------------------------
 * local, development, production
 * ----------------------------------------------------------------
 */
$ambiente = 'local';
if ($_SERVER['HTTP_HOST'] == 'local.amazon.development')
{
    $ambiente = 'production';
}
if ($_SERVER['HTTP_HOST'] == 'local.amazon.production')
{
    $ambiente = 'development';
}
define('APP_ENV', $ambiente);
/**
 * ----------------------------------------------------------------
 * FIM - TRATAMENTO DE AMBIENTE
 * ----------------------------------------------------------------
 */

if(APP_ENV != 'production')
{
    ini_set('display_errors',true);
    ini_set('error_reporting', E_ALL | E_NOTICE);
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

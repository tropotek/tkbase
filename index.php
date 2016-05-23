<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 */

$appPath = __DIR__;
include($appPath . '/vendor/autoload.php');

$config = \Tk\Config::getInstance();

//var_dump($config->getSiteRoutes());
//vd('-------------');

$request = \Tk\Request::create();
var_dump($request->getHeaders());

/**
$kernel = new App\Kernel($config->getEventDispatcher(), $config->getControllerResolver());
// Enable Cache
$kernel = new HttpCache($kernel, new Store($config->getCachePath()));
$kernel->handle($config->getRequest())->send();
$kernel->terminate($config->getRequest(), new Response());
*/


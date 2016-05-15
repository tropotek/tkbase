<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 */
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

$appPath = __DIR__;
include($appPath . '/vendor/autoload.php');

$config = \Tk\Config::getInstance();

$kernel = new App\Kernel($config->getEventDispatcher(), $config->getControllerResolver());
// Enable Cache
$kernel = new HttpCache($kernel, new Store($config->getCachePath()));
$kernel->handle($config->getRequest())->send();
$kernel->terminate($config->getRequest(), new Response());

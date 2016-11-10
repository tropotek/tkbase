<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 */

$appPath = __DIR__;
$composer = include($appPath . '/vendor/autoload.php');
\App\Factory::getConfig()->setComposer($composer);

// Execute Application controller
$request = \App\Factory::getRequest();
$kernel = \App\Factory::getFrontController();
$response = $kernel->handle($request)->send();
$kernel->terminate($request, $response);

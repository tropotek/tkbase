<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 *
 * Use this as the bootstrap file for all php files
 */

$sitePath = dirname(__FILE__);
/** @var \Composer\Autoload\ClassLoader $composer */
$composer = include($sitePath . '/vendor/autoload.php');
<<<<<<< HEAD
=======

$config = \App\Config::create($sitePath);
include($config->getSrcPath() . '/config/application.php');
$config->set('composer', $composer);
>>>>>>> 573c23c28fe7fda9066c66f2276cc1d0f6d44197

include_once $sitePath.'/src/App/Bootstrap.php';

\App\Config::getInstance()->setComposer($composer);

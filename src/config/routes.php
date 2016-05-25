<?php
/**
 * Created by PhpStorm.
 *
 * @date 16-05-2016
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */

$config = \Tk\Config::getInstance();

$routes = new \Tk\Routing\RouteCollection();
$config['site.routes'] = $routes;

// Logout
$routes->add('home', new \Tk\Routing\Route('/index.html', 'App\Controller\Index::doDefault',
    array('param1' => 'value1')     // Params that can be sent to the controller...
));
$routes->add('home2', new \Tk\Routing\Route('/home', 'App\Controller\Index::doDefault',
    array('param1' => 'value1')     // Params that can be sent to the controller...
));

$routes->add('contact', new \Tk\Routing\Route('/contact.html', 'App\Controller\Contact::doDefault',
    array('param2' => 'value2')     // Params that can be sent to the controller...
));







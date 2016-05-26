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
    array('param1' => 'value1')
));


// To enable variables in the routs we need to look at Symfony\Component\Routing/RouteCompiler.....????
// This will be needed if we want dynamic pages...
$routes->add('home2', new \Tk\Routing\Route('/home/{page}', 'App\Controller\Index::doDefault',
    array('page' => 'index.html')
));

$routes->add('contact', new \Tk\Routing\Route('/contact.html', 'App\Controller\Contact::doDefault',
    array('param2' => 'value2')
));







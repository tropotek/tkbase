<?php
/**
 * Created by PhpStorm.
 *
 * @date 16-05-2016
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */

/* Route Structure
 * $route = new Route(
 *     '/archive/{month}',              // path
 *     '\Namespace\Class::method',      // Callable or class::method string
 *     array('month' => 'Jan'),         // Params and defaults to path params... all will be sent to the request object.
 *     array('GET', 'POST', 'HEAD')     // methods
 * );
 */

$config = \Tk\Config::getInstance();

$routes = new \Tk\Routing\RouteCollection();
$config['site.routes'] = $routes;

// NOTE: Be sure to add routes in correct order as the first match will win


// Default Home catchall
$routes->add('home', new \Tk\Routing\Route('/index.html', 'App\Controller\Index::doDefault'));
$routes->add('home1', new \Tk\Routing\Route('/', 'App\Controller\Index::doDefault'));
$routes->add('contact', new \Tk\Routing\Route('/contact.html', 'App\Controller\Contact::doDefault'));

$routes->add('login', new \Tk\Routing\Route('/login.html', 'App\Controller\Login::doDefault'));
$routes->add('logout', new \Tk\Routing\Route('/logout.html', 'App\Controller\Logout::doDefault'));




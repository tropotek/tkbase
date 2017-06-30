<?php
/**
 * @date 16-05-2016
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 *
 * NOTE: Be sure to add routes in correct order as the first match will win
 * 
 * Route Structure
 * $route = new Route(
 *     '/archive/{month}',              // path
 *     '\Namespace\Class::method',      // Callable or class::method string
 *     array('month' => 'Jan'),         // Params and defaults to path params... all will be sent to the request object.
 *     array('GET', 'POST', 'HEAD')     // methods
 * );
 */

$config = \Tk\Config::getInstance();
/** @var \Tk\Routing\RouteCollection $routes */
$routes = $config->getSiteRoutes();


$params = array('role' => \App\Db\User::ROLE_USER);

$routes->add('ajax-test-one', new \Tk\Routing\Route('/api/1.0/testOne', 'App\Ajax\Test::doTestOne', $params, array('GET')));
$routes->add('ajax-test-two', new \Tk\Routing\Route('/api/1.0/testTwo', 'App\Ajax\Test::doTestTwo', $params, array('POST')));


$routes->add('ajax-user-find', new \Tk\Routing\Route('/api/1.0/findUser', 'App\Ajax\User::doFindUser', $params, array('GET')));








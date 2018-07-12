<?php
/* 
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

$config = \App\Config::getInstance();
$routes = $config->getRouteCollection();

// Public Pages
$params = array();

$routes->add('events', new \Tk\Routing\Route('/events.html', 'App\Controller\EventView::doDefault', array()));
$routes->add('send', new \Tk\Routing\Route('/send.html', 'App\Controller\Send::doDefault', $params));

// Admin Pages
$params = array('role' => \Bs\Db\User::ROLE_ADMIN);
$routes->add('admin-dashboard', new \Tk\Routing\Route('/admin/index.html', 'App\Controller\Admin\Dashboard::doDefault', $params));
$routes->add('admin-dashboard-base', new \Tk\Routing\Route('/admin/', 'App\Controller\Admin\Dashboard::doDefault', $params));
$routes->add('admin-settings', new \Tk\Routing\Route('/admin/settings.html', 'App\Controller\Admin\Settings::doDefault', $params));

// User Pages
$params = array('role' => \Bs\Db\User::ROLE_USER);
$routes->add('user-dashboard', new \Tk\Routing\Route('/user/index.html', 'App\Controller\User\Dashboard::doDefault', $params));
$routes->add('user-dashboard-base', new \Tk\Routing\Route('/user/', 'App\Controller\User\Dashboard::doDefault', $params));


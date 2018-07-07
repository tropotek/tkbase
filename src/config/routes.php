<?php
/**
 * Created by PhpStorm.
 *
 * @date 16-05-2016
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */

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
$routes = $config->getSiteRoutes();

include dirname(__FILE__) . '/ajaxRoutes.php';


// Default Home catchall
$params = array();

// Controller Pages (formsa and DB content, etc...)
$routes->add('events', new \Tk\Routing\Route('/events.html', 'App\Controller\EventView::doDefault', array()));
$routes->add('subscribe', new \Tk\Routing\Route('/subscribe.html', 'App\Controller\Subscriber::doDefault', $params));
$routes->add('send', new \Tk\Routing\Route('/send.html', 'App\Controller\Send::doDefault', $params));

// Admin Pages
$params = array('role' => \App\Db\User::ROLE_ADMIN);

$routes->add('admin-subscriber-manager', new \Tk\Routing\Route('/admin/subscriberManager.html', 'App\Controller\Admin\Subscriber\Manager::doDefault', $params));
$routes->add('admin-subscriber-edit', new \Tk\Routing\Route('/admin/subscriberEdit.html', 'App\Controller\Admin\Subscriber\Edit::doDefault', $params));

$routes->add('admin-event-manager', new \Tk\Routing\Route('/admin/eventManager.html', 'App\Controller\Admin\Event\Manager::doDefault', $params));
$routes->add('admin-event-edit', new \Tk\Routing\Route('/admin/eventEdit.html', 'App\Controller\Admin\Event\Edit::doDefault', $params));


// User Pages
$params = array('role' => \App\Db\User::ROLE_USER);
//$routes->add('user-profile', new \Tk\Routing\Route('/user/profile.html', 'App\Controller\Admin\User\Profile::doDefault', $params));





// Examples
$params = array();

// Ajax Routes
$routes->add('ajax-find-user', new \Tk\Routing\Route('/ajax/findUser', 'App\Ajax\User::doFindUser', $params, array('POST')));

// Example: How to do a simple inline controller all-in-one
$routes->add('simpleTest', new \Tk\Routing\Route('/test.html', function ($request) use ($config) {
    vd($config->toString());
    return '<p>This is a simple test</p>';
}, $params));

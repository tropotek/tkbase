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
$routes->add('subscribe', new \Tk\Routing\Route('/subscribe.html', 'App\Controller\Subscriber::doDefault', $params));
$routes->add('send', new \Tk\Routing\Route('/send.html', 'App\Controller\Send::doDefault', $params));

// Admin Pages
$params = array('role' => \Bs\Db\User::ROLE_ADMIN);
$routes->add('admin-dashboard', new \Tk\Routing\Route('/admin/index.html', 'App\Controller\Admin\Dashboard::doDefault', $params));
$routes->add('admin-dashboard-base', new \Tk\Routing\Route('/admin/', 'App\Controller\Admin\Dashboard::doDefault', $params));
$routes->add('admin-settings', new \Tk\Routing\Route('/admin/settings.html', 'App\Controller\Admin\Settings::doDefault', $params));

$routes->add('admin-subscriber-manager', new \Tk\Routing\Route('/admin/subscriberManager.html', 'App\Controller\Admin\Subscriber\Manager::doDefault', $params));
$routes->add('admin-subscriber-edit', new \Tk\Routing\Route('/admin/subscriberEdit.html', 'App\Controller\Admin\Subscriber\Edit::doDefault', $params));
$routes->add('admin-event-manager', new \Tk\Routing\Route('/admin/eventManager.html', 'App\Controller\Admin\Event\Manager::doDefault', $params));
$routes->add('admin-event-edit', new \Tk\Routing\Route('/admin/eventEdit.html', 'App\Controller\Admin\Event\Edit::doDefault', $params));

$routes->add('dev-form', new \Tk\Routing\Route('/admin/dev/form.html', 'App\Controller\Admin\Dev\Form::doDefault', $params));

// User Pages
$params = array('role' => \Bs\Db\User::ROLE_USER);
$routes->add('user-dashboard', new \Tk\Routing\Route('/user/index.html', 'App\Controller\User\Dashboard::doDefault', $params));
$routes->add('user-dashboard-base', new \Tk\Routing\Route('/user/', 'App\Controller\User\Dashboard::doDefault', $params));





// Examples
$params = array();

// Ajax Routes
//$routes->add('ajax-find-user', new \Tk\Routing\Route('/api/1.0/findUser', 'App\Ajax\User::doFindUser', $params, array('POST')));

// Example: How to do a simple inline controller all-in-one
//$routes->add('simpleTest', new \Tk\Routing\Route('/test.html', function ($request) use ($config) {
//    vd($config->toString());
//    return '<p>This is a simple test</p>';
//}, $params));

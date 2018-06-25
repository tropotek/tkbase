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

$config = \Tk\Config::getInstance();
$routes = new \Tk\Routing\RouteCollection();
$config['site.routes'] = $routes;
include dirname(__FILE__) . '/ajaxRoutes.php';


// Default Home catchall
$params = array();
// Used to redirect index.php request back home
$routes->add('public-index-php-fix', new \Tk\Routing\Route('/index.php', function ($request) use ($config) {
    \Tk\Uri::create('/')->redirect();
}, $params));
$routes->add('home', new \Tk\Routing\Route('/index.html', 'App\Controller\Index::doDefault', $params));
$routes->add('home-base', new \Tk\Routing\Route('/', 'App\Controller\Index::doDefault', $params));
$routes->add('contact', new \Tk\Routing\Route('/contact.html', 'App\Controller\Contact::doDefault', $params));

$routes->add('login', new \Tk\Routing\Route('/login.html', 'App\Controller\Login::doDefault', $params));
$routes->add('logout', new \Tk\Routing\Route('/logout.html', 'App\Controller\Logout::doDefault', $params));
$routes->add('register', new \Tk\Routing\Route('/register.html', 'App\Controller\Register::doDefault', $params));
$routes->add('recover', new \Tk\Routing\Route('/recover.html', 'App\Controller\Recover::doDefault', $params));



// Controller Pages (formsa and DB content, etc...)
$routes->add('events', new \Tk\Routing\Route('/events.html', 'App\Controller\EventView::doDefault', array()));
$routes->add('subscribe', new \Tk\Routing\Route('/subscribe.html', 'App\Controller\Subscriber::doDefault', $params));
$routes->add('send', new \Tk\Routing\Route('/send.html', 'App\Controller\Send::doDefault', $params));

// Admin Pages
$params = array('role' => \App\Db\User::ROLE_ADMIN);
$routes->add('admin-home', new \Tk\Routing\Route('/admin/index.html', 'App\Controller\Admin\Index::doDefault', $params));
$routes->add('admin-home-base', new \Tk\Routing\Route('/admin/', 'App\Controller\Admin\Index::doDefault', $params));

$routes->add('admin-user-manager', new \Tk\Routing\Route('/admin/userManager.html', 'App\Controller\Admin\User\Manager::doDefault', $params));
$routes->add('admin-user-edit', new \Tk\Routing\Route('/admin/userEdit.html', 'App\Controller\Admin\User\Edit::doDefault', $params));
$routes->add('admin-user-profile', new \Tk\Routing\Route('/admin/profile.html', 'App\Controller\Admin\User\Profile::doDefault', $params));

$routes->add('admin-settings', new \Tk\Routing\Route('/admin/settings.html', 'App\Controller\Admin\Settings::doDefault', $params));
$routes->add('admin-plugin-manager', new \Tk\Routing\Route('/admin/plugins.html', 'App\Controller\Admin\PluginManager::doDefault', $params));

$routes->add('admin-subscriber-manager', new \Tk\Routing\Route('/admin/subscriberManager.html', 'App\Controller\Admin\Subscriber\Manager::doDefault', $params));
$routes->add('admin-subscriber-edit', new \Tk\Routing\Route('/admin/subscriberEdit.html', 'App\Controller\Admin\Subscriber\Edit::doDefault', $params));

$routes->add('admin-event-manager', new \Tk\Routing\Route('/admin/eventManager.html', 'App\Controller\Admin\Event\Manager::doDefault', $params));
$routes->add('admin-event-edit', new \Tk\Routing\Route('/admin/eventEdit.html', 'App\Controller\Admin\Event\Edit::doDefault', $params));

$routes->add('admin-maillog-manager', new \Tk\Routing\Route('/admin/mailLogManager.html', 'App\Controller\Admin\MailLog\Manager::doDefault', $params));
$routes->add('admin-maillog-view', new \Tk\Routing\Route('/admin/mailLogView.html', 'App\Controller\Admin\MailLog\View::doDefault', $params));

// Dev pages
$routes->add('dev-events', new \Tk\Routing\Route('/admin/dev/events.html', 'App\Controller\Admin\Dev\Events::doDefault', $params));
$routes->add('dev-form', new \Tk\Routing\Route('/admin/dev/form.html', 'App\Controller\Admin\Dev\Form::doDefault', $params));

// User Pages
$params = array('role' => \App\Db\User::ROLE_USER);
$routes->add('user-home', new \Tk\Routing\Route('/user/index.html', 'App\Controller\User\Index::doDefault', $params));
$routes->add('user-home-base', new \Tk\Routing\Route('/user/', 'App\Controller\User\Index::doDefault', $params));
$routes->add('user-profile', new \Tk\Routing\Route('/user/profile.html', 'App\Controller\Admin\User\Profile::doDefault', $params));


$params = array();
// Example: How to do a simple controller/route all-in-one
$routes->add('simpleTest', new \Tk\Routing\Route('/test.html', function ($request) use ($config) {
    vd($config->toString());
    return '<p>This is a simple test</p>';
}, $params));

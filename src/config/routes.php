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
use Tk\Routing\Route;

$config = \App\Config::getInstance();
$routes = $config->getRouteCollection();
if (!$routes) return;

// Public Pages
$routes->add('home', Route::create('/index.html', 'App\Controller\Index::doDefault'));
$routes->add('home-base', Route::create('/', 'App\Controller\Index::doDefault'));

$routes->add('login', Route::create('/login.html', 'App\Controller\Login::doDefault'));
$routes->add('recover', Route::create('/recover.html', 'App\Controller\Recover::doDefault'));
$routes->add('register', Route::create('/register.html', 'App\Controller\Register::doDefault'));

$routes->add('privacy', Route::create('/privacy.html', 'App\Controller\Privacy::doDefault'));
$routes->add('terms', Route::create('/terms.html', 'App\Controller\Terms::doDefault'));
$routes->add('contact', Route::create('/contact.html', 'App\Controller\Contact::doDefault'));
$routes->add('send', Route::create('/send.html', 'App\Controller\Send::doDefault'));

// Admin Pages
$routes->add('admin-dashboard', Route::create('/admin/index.html', 'App\Controller\Admin\Dashboard::doDefault'));
$routes->add('admin-dashboard-base', Route::create('/admin/', 'App\Controller\Admin\Dashboard::doDefault'));
$routes->add('admin-settings', Route::create('/admin/settings.html', 'App\Controller\Admin\Settings::doDefault'));

// User Pages
$routes->add('member-dashboard', Route::create('/member/index.html', 'App\Controller\Member\Dashboard::doDefault'));
$routes->add('member-dashboard-base', Route::create('/member/', 'App\Controller\Member\Dashboard::doDefault'));

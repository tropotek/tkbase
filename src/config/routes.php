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

$router = new \Tk\Routing\Router();
$config['site.routes'] = $router;



// Logout
$router->add('home', new \Tk\Routing\Route('/index.html', 'App\Controller\Index::doDefault', 
    array('param1' => 'value1')     // Params that can be sent to the controller...
));



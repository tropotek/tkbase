<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
$config = \Tk\Config::getInstance();

/**
 * Config the session using PHP option names prepended with 'session.'
 * @see http://php.net/session.configuration
 */
include_once(__DIR__ . '/session.php');
include_once(__DIR__ . '/routes.php');

$config['site.title'] = 'Tk2Uni Site';
$config['site.email'] = 'tkwiki@example.com';

//$config['site.meta.keywords'] = '';
//$config['site.meta.description'] = '';
//$config['site.global.js'] = '';
//$config['site.global.css'] = '';


// Template folders for pages
$config['template.admin.path'] = '/html/admin';
$config['template.public.path'] = '/html/purpose';


// -- AUTH CONFIG --

// Hash function to use for authentication
// Warning: do not change after install, or else
//   ALL existing passwords will be invalid and need to be reset.
$config['hash.function'] = 'md5';

//$config['site.client.registration'] = false;
//$config['site.client.activation'] = false;

// Authentication adapters
$config['system.auth.adapters'] = array(
    'DbTable' => '\Tk\Auth\Adapter\DbTable',
    //'Config' => '\Tk\Auth\Adapter\Config',
    'Trap' => '\Tk\Auth\Adapter\Trapdoor'
    //'LDAP' => '\Tk\Auth\Adapter\Ldap'
);

// \Tk\Auth\Adapter\DbTable
$config['system.auth.dbtable.tableName'] = 'user';
$config['system.auth.dbtable.usernameColumn'] = 'username';
$config['system.auth.dbtable.passwordColumn'] = 'password';
$config['system.auth.dbtable.activeColumn'] = 'active';

// \Tk\Auth\Adapter\Config
//$config['system.auth.username'] = 'admin';
//$config['system.auth.password'] = 'password';



/**
 * Set the system timezone
 */
$config['date.timezone'] = 'Australia/Victoria';


// To avoid var dump errors when debug lib not present
// TODO: there could be a better way to handle this in the future 
if (!class_exists('\Tk\Vd')) {
    function vd() {}
    function vdd() {}
}
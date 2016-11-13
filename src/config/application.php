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

/*
 * Setup any default app config values
 */
$config['site.title'] = 'Tk2 Site';
$config['site.email'] = 'user@example.com';
//$config['site.client.registration'] = false;
//$config['site.client.activation'] = false;

/*
 * Template folders for pages
 */
$config['template.admin.path'] = '/html/default';
$config['template.public.path'] = '/html/purpose';

/**
 * Set the system timezone
 */
//$config['date.timezone'] = 'Australia/Victoria';



/*  
 * ---- AUTH CONFIG ----
 */

/*
 * The hash function to use for passwords and general hashing
 * Warning if you change this after user account creation
 * users will have to reset/recover their passwords
 */
//$config['hash.function'] = 'md5';


// \Tk\Auth\Adapter\DbTable
$config['system.auth.dbtable.tableName'] = 'user';
$config['system.auth.dbtable.usernameColumn'] = 'username';
$config['system.auth.dbtable.passwordColumn'] = 'password';
$config['system.auth.dbtable.activeColumn'] = 'active';

/*
 * Config for the \Tk\Auth\Adapter\DbTable
 */
$config['system.auth.adapters'] = array(
    'DbTable' => '\Tk\Auth\Adapter\DbTable',
    //'Config' => '\Tk\Auth\Adapter\Config',
    'Trap' => '\Tk\Auth\Adapter\Trapdoor'
    //'LDAP' => '\Tk\Auth\Adapter\Ldap'
);

/*
 * \Tk\Auth\Adapter\Config
 */
//$config['system.auth.username'] = 'admin';
//$config['system.auth.password'] = 'password';






<?php
/*
 * Application default config values
 * This file should not need to be edited
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */

$config = \App\Config::getInstance();

/**************************************
 * Default app config values
 **************************************/



/*
 * Template folders for pages
 */
$config['system.template.path']     = '/html';

$config['system.theme.public']      = $config['system.template.path']   . '/public';
$config['system.theme.admin']       = $config['system.template.path']   . '/admin';

$config['template.admin']           = $config['system.theme.admin']     . '/admin.html';
$config['template.user']            = $config['system.theme.admin']     . '/admin.html';
$config['template.public']          = $config['system.theme.public']    . '/public.html';

$config['template.login']           = $config['system.theme.admin']     . '/login.html';

/*
 * Set the error page template
 */
$config['template.error']           = $config['system.template.path']   . '/theme-cube/error.html';

/*
 * Set the maintenance page template
 */
$config['template.maintenance']     = $config['system.template.path']   . '/theme-cube/maintenance.html';

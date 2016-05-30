<?php
namespace App\Page;

use Tk\Request;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class AdminPage extends Iface
{
    
    /**
     * AdminPage constructor.
     *
     * @param \App\Controller\Iface $controller
     */
    public function __construct(\App\Controller\Iface $controller)
    {
        // Security/permissions etc..
        /** @var \Tk\Auth\Auth $auth */
        $auth = \Tk\Config::getInstance()->getAuth();

        if (!$auth->getIdentity()) {
            \Tk\Uri::create('/login.html')->redirect();
            //throw new UnauthorizedHttpException('You do not have permission to access this resource.');
        }
        parent::__construct($controller);
    }


    public function show()
    {
        $template = $this->getTemplate();

        $this->showAlerts();
    }


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $tplFile =  \Tk\Config::getInstance()->getAdminTemplatePath().'/index.html';
        return \Dom\Loader::loadFile($tplFile);
    }

}
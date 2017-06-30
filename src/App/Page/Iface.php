<?php
namespace App\Page;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
abstract class Iface extends \Tk\Controller\Page
{
    
    /**
     * Iface constructor.
     *
     * @param \App\Controller\Iface $controller
     */
    public function __construct(\App\Controller\Iface $controller)
    {
        parent::__construct($controller);
    }

    /**
     * Set the page heading, should be set from main controller
     *
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    protected function initPage()
    {
        $template = parent::show();

        if ($this->controller->getUser()) {
            $template->setChoice('logout');
        } else {
            $template->setChoice('login');
        }
        if ($this->getConfig()->isDebug()) {
            $template->setChoice('debug');
        }

        return $template;
    }

    /**
     * Get the currently logged in user
     *
     * @return \App\Db\User
     */
    public function getUser()
    {
        return $this->getConfig()->getUser();
    }

}
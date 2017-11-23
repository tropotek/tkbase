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
     * Set the page heading, should be set from main controller
     *
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function show()
    {
        $template = parent::show();

        if (\Tk\AlertCollection::hasMessages()) {
            $template->insertTemplate('alerts', \Tk\AlertCollection::getInstance()->show());
            $template->setChoice('alerts');
        }

        if ($this->controller->getUser()) {
            $template->setChoice('logout');
            $template->setAttr('homeUrl', 'href', $this->controller->getUser()->getHomeUrl());
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
        vd($this->getConfig());
        return $this->getConfig()->getUser();
    }

}
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
     * @throws \Exception
     */
    public function show()
    {
        $template = parent::show();

        $template->hide('alerts');
        if (\Tk\AlertCollection::hasMessages()) {
            $template->insertTemplate('alerts', \Tk\AlertCollection::getInstance()->show());
            $template->show('alerts');
        }

        $user = $this->getUser();
        $template->hide('login');
        $template->hide('logout');
        if ($user) {
            $template->show('logout');
            $template->setAttr('user-home', 'href', $user->getHomeUrl());
        } else {
            $template->show('login');
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
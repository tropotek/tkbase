<?php
namespace App\Controller;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
abstract class Iface extends \Tk\Controller\Iface
{

    /**
     * Get a new instance of the page to display the content in.
     *
     * @return \App\Page\Iface
     */
    public function getPage()
    {
        $role = $this->getConfig()->getRequest()->getAttribute('role');
        if (is_array($role)) $role = current($role);

        if (!$this->page) {
            switch($role) {
                case \App\Db\User::ROLE_ADMIN:
                    $this->page = new \App\Page\AdminPage();
                    break;
                default:
                    $this->page = new \App\Page\PublicPage();
                    break;
            }
            $this->page->setController($this);
        }
        return $this->page;
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

    /**
     * @return \App\Config
     */
    public function getConfig()
    {
        return parent::getConfig();
    }

    /**
     * DomTemplate magic method example
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $html = <<<HTML
<div></div>
HTML;
        return \Dom\Loader::load($html);
        // OR FOR A FILE
        //return \Dom\Loader::loadFile($this->getTemplatePath().'/public.xtpl');
    }

}
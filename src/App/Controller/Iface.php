<?php
namespace App\Controller;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
abstract class Iface extends \Dom\Renderer\Renderer
{

    /**
     * @var string
     */
    protected $pageTitle = '';
    
    /**
     * @var \App\Page\Iface
     */
    protected $page = null;


    /**
     * @param string $pageTitle
     */
    public function __construct($pageTitle = '')
    {
        $this->setPageTitle($pageTitle);
        $this->getPage();
    }

    /**
     * Get a new instance of the page to display the content in.
     *
     * @return \App\Page\Iface
     */
    public function getPage()
    {
        $pageAccess = $this->getConfig()->getRequest()->getAttribute('access');

        if (!$this->page) {
            switch($pageAccess) {
                case \App\Auth\Acl::ROLE_ADMIN:
                    $this->page = new \App\Page\AdminPage($this);
                    break;
                default:
                    $this->page = new \App\Page\PublicPage($this);
                    break;
            }
        }
        return $this->page;
    }

    /**
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->getPage()->getTemplatePath();
    }

    /**
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     *
     * @param string $pageTitle
     * @return $this
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    /**
     * Get the global config object.
     *
     * @return \Tk\Config
     */
    public function getConfig()
    {
        return \Tk\Config::getInstance();
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
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
     * @return string
     * @todo: we should come up with a more solid routing naming convention
     */
    public function getDefaultTitle()
    {
        $replace = array('admin-', 'client-', 'staff-', 'student-', '-base');
        /** @var \Tk\Request $request */
        $request = $this->getConfig()->getRequest();
        if ($request) {
            $routeName = $request->getAttribute('_route');
            $routeName = str_replace($replace, '', $routeName);
            return ucwords(trim(str_replace('-', ' ', $routeName)));
        }
        return '';
    }

    /**
     * @return string
     */
    public function getPageRole()
    {
        $role = $this->getRequest()->getAttribute('role');
        if (is_array($role)) $role = current($role);
        return $role;
    }

    /**
     * Get a new instance of the page to display the content in.
     *
     * @return \App\Page\Iface
     */
//    public function getPage()
//    {
//        $role = $this->getConfig()->getRequest()->getAttribute('role');
//        if (is_array($role)) $role = current($role);
//
//        if (!$this->page) {
//            switch($role) {
//                case \App\Db\User::ROLE_ADMIN:
//                    $this->page = new \App\Page\AdminPage();
//                    break;
//                default:
//                    $this->page = new \App\Page\PublicPage();
//                    break;
//            }
//            $this->page->setController($this);
//        }
//        return $this->page;
//    }

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
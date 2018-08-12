<?php
namespace App\Listener;

use Tk\Event\Subscriber;
use Tk\Kernel\KernelEvents;

/**
 * This object helps cleanup the structure of the controller code
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class NavigationHandler implements Subscriber
{

    /**
     * @param \Tk\Event\Event $event
     */
    public function onPageShow(\Tk\Event\Event $event)
    {
        $config = \App\Config::getInstance();
        $controller = $event->get('controller');
        if ($controller instanceof \Bs\Controller\Iface) {
            $page = $controller->getPage();
            if (!$page) return;
            $template = $page->getTemplate();
            /** @var \Bs\Db\User $user */
            $user = $controller->getUser();
            if ($user) {
                // Insert the side nav
                $sidebar = $this->createNavSideBar($user);
                if ($sidebar) {
                    $template->replaceTemplate($config->get('template.var.page.side-nav'), $sidebar->show());
                }

                $dropdownNav = $this->createDropdownNav($user);
                if ($dropdownNav)
                    $template->replaceTemplate('dropdown-nav', $dropdownNav->show());

//                $actionsNav = $this->createNewActionsNav($user);
//                if ($actionsNav)
//                    $template->appendTemplate('header-nav-left', $actionsNav->show());


                // Insert the user nav


                // ....
            }
        }
    }

    /**
     * @param \Bs\Db\User $user
     * @return null|\Dom\Renderer\DisplayInterface
     */
    protected function createNavSideBar($user)
    {
        $nav = null;
        switch ($user->getRoleType()) {
            case \Bs\Db\Role::TYPE_ADMIN:
                $nav = new \App\Ui\Menu\AdminSideNav();
                break;
            case \Bs\Db\Role::TYPE_USER:
                $nav = new \App\Ui\Menu\UserSideNav();
                break;
        }
        return $nav;
    }

    /**
     * @param \Bs\Db\User $user
     * @return null|\Dom\Renderer\DisplayInterface
     */
    protected function createDropdownNav($user)
    {
        $nav = null;
        switch ($user->getRoleType()) {
            case \Bs\Db\Role::TYPE_ADMIN:
                $nav = new \App\Ui\Menu\AdminDropdownNav();
                break;
            case \Bs\Db\Role::TYPE_USER:
                $nav = new \App\Ui\Menu\UserDropdownNav();
                break;
        }
        return $nav;
    }

    /**
     * @param \Bs\Db\User $user
     * @return null|\Dom\Renderer\DisplayInterface
     */
    protected function createNewActionsNav($user)
    {
        $nav = null;
        switch ($user->getRoleType()) {
            case \Bs\Db\Role::TYPE_ADMIN:
                $nav = new \App\Ui\Menu\AdminActionsNav();
                break;
        }
        return $nav;
    }

    
    /**
     * getSubscribedEvents
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            \Tk\PageEvents::PAGE_SHOW =>  array('onPageShow', 0)
        );
    }
}
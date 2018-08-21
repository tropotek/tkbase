<?php
namespace App\Listener;

use Tk\Event\Subscriber;
use Tk\Kernel\KernelEvents;
use Tk\Ui\Menu\Item;
use Bs\Ui\Menu;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2018 Michael Mifsud
 */
class NavRendererHandler implements Subscriber
{
    /**
     * @var Menu
     */
    protected $dropdownMenu = null;

    /**
     * @var Menu
     */
    protected $sideMenu = null;



    /**
     * @param \Tk\Event\GetResponseEvent $event
     */
    public function onRequest(\Tk\Event\GetResponseEvent $event)
    {
        $config = $this->getConfig();
        $role = 'public';
        if ($config->getUser())
            $role = $config->getUser()->getRoleType();
        if (is_array($role)) $role = current($role);

        $this->dropdownMenu = $config->getMenuManager()->getMenu('nav-dropdown', $role);
        $this->sideMenu = $config->getMenuManager()->getMenu('nav-side', $role);

        $this->dropdownMenu->setAttr('style', 'visibility:hidden;');
        $this->sideMenu->setAttr('style', 'visibility:hidden;');

        $this->initDropdownMenu($this->dropdownMenu);
        $this->initSideMenu($this->sideMenu);

    }

    /**
     * @param Menu $menu
     */
    protected function initDropdownMenu($menu)
    {
        $menu->append(Item::create('Profile', \Bs\Uri::createHomeUrl('/profile.html'), 'fa fa-user'));
        switch ($menu->getRoleType()) {
            case \Bs\Db\Role::TYPE_ADMIN:
                $menu->prepend(Item::create('Site Preview', \Bs\Uri::create('/index.html'), 'fa fa-home'))->getLink()
                    ->setAttr('target', '_blank');
                $menu->append(Item::create('Settings', \Bs\Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
                break;
            case \Bs\Db\Role::TYPE_USER:
                break;
        }
        $menu->append(Item::create('About', '#', 'fa fa-info-circle')
            ->setAttr('data-toggle', 'modal')->setAttr('data-target', '#aboutModal'));
        $menu->append(Item::create()->addCss('divider'));
        $menu->append(Item::create('Logout', '#', 'fa fa-sign-out')
            ->setAttr('data-toggle', 'modal')->setAttr('data-target', '#logoutModal'));
        //vd($menu->__toString());
    }

    /**
     * @param Menu $menu
     */
    protected function initSideMenu($menu)
    {
        $user = $this->getConfig()->getUser();

        $menu->append(Item::create('Dashboard', \Bs\Uri::createHomeUrl('/index.html'), 'fa fa-dashboard'));

        switch ($menu->getRoleType()) {
            case \Bs\Db\Role::TYPE_ADMIN:
                $menu->append(Item::create('Settings', \Bs\Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
                if ($this->getConfig()->isDebug()) {
                    $sub = $menu->append(Item::create('Development', '#', 'fa fa-bug'));
                    $sub->append(Item::create('Events', \Bs\Uri::createHomeUrl('/dev/dispatcherEvents.html'), 'fa fa-empire'));
                }
                break;
            case \Bs\Db\Role::TYPE_USER:
                break;
        }
        //vd($menu->__toString());
    }




    /**
     * @param \Tk\Event\Event $event
     */
    public function onShow(\Tk\Event\Event $event)
    {
        $controller = $event->get('controller');
        if ($controller instanceof \Bs\Controller\Iface) {
            /** @var \Bs\Page $page */
            $page = $controller->getPage();
            $template = $page->getTemplate();

            foreach ($this->getConfig()->getMenuManager()->getMenuList() as $menu) {
                $renderer = \Tk\Ui\Menu\ListRenderer::create($menu);
                $tpl = $renderer->show();
                $template->replaceTemplate($menu->getTemplateVar(), $tpl);
            }
        }
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            \Tk\Kernel\KernelEvents::REQUEST =>  array('onRequest', 0),
            \Tk\PageEvents::PAGE_SHOW =>  array('onShow', 0)
        );
    }

    /**
     * @return \Tk\Config|\Bs\Config
     */
    public function getConfig()
    {
        return \Bs\Config::getInstance();
    }
}
<?php
namespace App\Listener;

use Symfony\Component\HttpKernel\KernelEvents;
use Tk\Event\Subscriber;
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
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     */
    public function onRequest($event)
    {
        $config = $this->getConfig();

        $dropdownMenu = $config->getMenuManager()->getMenu('nav-dropdown');
        $sideMenu = $config->getMenuManager()->getMenu('nav-side');

        $dropdownMenu->setAttr('style', 'visibility:hidden;');
        $sideMenu->setAttr('style', 'visibility:hidden;');

        $this->initDropdownMenu($dropdownMenu);
        $this->initSideMenu($sideMenu);

    }

    /**
     * @param Menu $menu
     */
    protected function initDropdownMenu($menu)
    {
        $user = $this->getConfig()->getAuthUser();
        if (!$user) return;

        $menu->append(Item::create('Profile', \Bs\Uri::createHomeUrl('/profile.html'), 'fa fa-user'));
        if ($user->hasPermission(\Bs\Db\Permission::TYPE_ADMIN)) {
            $menu->prepend(Item::create('Site Preview', \Bs\Uri::create('/index.html'), 'fa fa-home'))->getLink()
                ->setAttr('target', '_blank');
            $menu->append(Item::create('Settings', \Bs\Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
        }

        $menu->append(Item::create('About', '#', 'fa fa-info-circle')
            ->setAttr('data-toggle', 'modal')->setAttr('data-target', '#aboutModal'));
        $menu->append(Item::create()->addCss('divider'));
        $menu->append(Item::create('Logout', '#', 'fa fa-sign-out')
            ->setAttr('data-toggle', 'modal')->setAttr('data-target', '#logoutModal'));

    }

    /**
     * @param Menu $menu
     */
    protected function initSideMenu($menu)
    {
        $user = $this->getConfig()->getAuthUser();
        if (!$user) return;

        $menu->append(Item::create('Dashboard', \Bs\Uri::createHomeUrl('/index.html'), 'fa fa-dashboard'));
        if ($user->hasPermission(\Bs\Db\Permission::TYPE_ADMIN)) {
            $menu->append(Item::create('Settings', \Bs\Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
            if ($this->getConfig()->isDebug()) {
                $sub = $menu->append(Item::create('Development', '#', 'fa fa-bug'));
                $sub->append(Item::create('Events', \Bs\Uri::createHomeUrl('/dev/dispatcherEvents.html'), 'fa fa-empire'));
            }
        }

    }




    /**
     * @param \Tk\Event\Event $event
     */
    public function onShow(\Tk\Event\Event $event)
    {
        $controller = \Tk\Event\Event::findControllerObject($event);
        if ($controller instanceof \Bs\Controller\Iface) {
            /** @var \Bs\Page $page */
            $page = $controller->getPage();
            $template = $page->getTemplate();

            foreach ($this->getConfig()->getMenuManager()->getMenuList() as $menu) {
                $renderer = \Tk\Ui\Menu\ListRenderer::create($menu);
                $tpl = $renderer->show();
                $template->replaceTemplate($menu->getName(), $tpl);
            }
        }
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST =>  array('onRequest', 0),
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
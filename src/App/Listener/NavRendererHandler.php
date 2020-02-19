<?php
namespace App\Listener;

use Bs\Controller\Iface;
use Bs\Db\User;
use Bs\Page;
use Bs\Uri;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Tk\ConfigTrait;
use Tk\Event\Event;
use Tk\Event\Subscriber;
use Tk\PageEvents;
use Tk\Ui\Menu\Item;
use Bs\Ui\Menu;
use Tk\Ui\Menu\ListRenderer;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2018 Michael Mifsud
 */
class NavRendererHandler implements Subscriber
{
    use ConfigTrait;

    /**
     * @param GetResponseEvent $event
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

        $menu->append(Item::create('Profile', Uri::createHomeUrl('/profile.html'), 'fa fa-user'));
        if ($user->hasType(User::TYPE_ADMIN)) {
            $menu->prepend(Item::create('Site Preview', Uri::create('/index.html'), 'fa fa-home'))->getLink()
                ->setAttr('target', '_blank');
            $menu->append(Item::create('Settings', Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
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

        $menu->append(Item::create('Dashboard', Uri::createHomeUrl('/index.html'), 'fa fa-dashboard'));
        if ($user->hasType(User::TYPE_ADMIN)) {
            $menu->append(Item::create('Settings', Uri::createHomeUrl('/settings.html'), 'fa fa-cogs'));
            if ($this->getConfig()->isDebug()) {
                $sub = $menu->append(Item::create('Development', '#', 'fa fa-bug'));
                $sub->append(Item::create('Events', Uri::createHomeUrl('/dev/dispatcherEvents.html'), 'fa fa-empire'));
            }
        }

    }


    /**
     * @param Event $event
     */
    public function onShow(Event $event)
    {
        $controller = Event::findControllerObject($event);
        if ($controller instanceof Iface) {
            /** @var Page $page */
            $page = $controller->getPage();
            $template = $page->getTemplate();

            foreach ($this->getConfig()->getMenuManager()->getMenuList() as $menu) {
                $renderer = ListRenderer::create($menu);
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
            PageEvents::PAGE_SHOW =>  array('onShow', 0)
        );
    }

}
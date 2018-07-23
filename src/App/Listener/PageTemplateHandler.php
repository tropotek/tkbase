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
class PageTemplateHandler extends \Bs\Listener\PageTemplateHandler
{

    /**
     * @param \Tk\Event\Event $event
     * @throws \Exception
     */
    public function showPage(\Tk\Event\Event $event)
    {
        parent::showPage($event);
        $controller = $event->get('controller');
        if ($controller instanceof \Bs\Controller\Iface) {
            $page = $controller->getPage();
            if (!$page) return;
            $template = $page->getTemplate();
            /** @var \Bs\Db\User $user */
            $user = $controller->getUser();


            // Add anything to the page template here ...


        }
    }


    /**
     * @return \App\Config|\Tk\Config
     */
    public function getConfig()
    {
        return \App\Config::getInstance();
    }

}
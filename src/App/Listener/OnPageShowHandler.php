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
class OnPageShowHandler implements Subscriber
{

//    public function onControllerShow(\Tk\Event\Event $event)
//    {
//        $controller = $event->get('controller');
//        if ($controller instanceof \Bs\Controller\Iface) {
//            $template = $controller->getTemplate();
//
//
//
//        }
//    }

    /**
     * @param \Tk\Event\Event $event
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     */
    public function onPageShow(\Tk\Event\Event $event)
    {
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
    
    /**
     * getSubscribedEvents
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            //\Tk\PageEvents::CONTROLLER_SHOW =>  array('onControllerShow', 0),
            \Tk\PageEvents::PAGE_SHOW =>  array('onPageShow', 0)
        );
    }
}
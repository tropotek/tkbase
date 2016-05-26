<?php

namespace App\Listener;


use Tk\EventDispatcher\SubscriberInterface;
use Tk\Event\GetResponseEvent;
use Tk\Event\ControllerResultEvent;
use Tk\Event\FilterResponseEvent;
use Tk\Kernel\KernelEvents;
use Tk\Response;


/**
 * Class ShutdownHandler
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class ResponseHandler implements SubscriberInterface
{
    

    /**
     * 
     *
     * @param ControllerResultEvent $event
     */
    public function domModify(ControllerResultEvent $event)
    {
        // TODO: Needs to be created in a factory
        $config = \Tk\Config::getInstance();
        $dm = new \Dom\Modifier\Modifier();
        $dm->add(new \Dom\Modifier\Filter\Path($config['site.url']));
        $dm->add(new \Dom\Modifier\Filter\JsLast());
        $config['dom.modifier'] = $dm;
        
        /* @var $template \Dom\Template */
        $result = $event->getControllerResult();

        if ($result instanceof \Dom\Renderer\Iface) {
            $result = $result->getTemplate()->getDocument();
        }
        if ($result instanceof \Dom\Template) {
            $dm->execute($result->getDocument());
        }
    }

    /**
     * NOTE: if you want to modify the template using its API
     * you must add the listeners before this one its priority is set to -1000
     * make sure your handlers have a priority > -1000
     * 
     * Convert controller return types to a request
     * Once this event is fired it will stop propagation, so other events
     * using this name must be run with a priority > 1000
     * 
     * @param ControllerResultEvent $event
     */
    public function convertControllerResult(ControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        
        if ($result instanceof \Dom\Template) {
            $event->setResponse(new Response($result->toString()));
        } else if ($result instanceof \Dom\Renderer\Iface) {
            $event->setResponse(new Response($result->getTemplate()->toString()));
        } else if (is_string($result)) {
            $event->setResponse(new Response($result));
        }
    }

    /**
     * Add any headers to the final response.
     * 
     * @param FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        
        // disable the browser cache as this is a dynamic site.
        $response->addHeader('Cache-Control', 'no-cache, must-revalidate');
        $response->addHeader('Expires', 'Mon, 1 Jan 2000 00:00:00 GMT');
        $response->addHeader('Pragma', 'no-cache');
        
    }
    

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => array(array('domModify'), array('convertControllerResult', -1000)),
            KernelEvents::RESPONSE => 'onResponse'
        );
    }
}
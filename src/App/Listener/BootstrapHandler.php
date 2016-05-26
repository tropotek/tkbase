<?php
namespace App\Listener;

use Tk\EventDispatcher\SubscriberInterface;
use Tk\Event\KernelEvent;
use Tk\Kernel\KernelEvents;


/**
 * Class StartupHandler
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class BootstrapHandler implements SubscriberInterface
{
    /**
     * @var array|\Tk\Config
     */
    protected $config = null;

    /**
     * @param array|\Tk\Config
     */
    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param KernelEvent $event
     */
    public function onInit(KernelEvent $event)
    {
        
        
        // TODO: These dependencies should be lazly loaded 
        // through a factory object or DI container.
        // I am still deciding what to do here....THis will do for now.
        
        
        
        // * Database init
        try {
            $pdo = \Tk\Db\Pdo::create($this->config->getGroup('db'));
            $logger = $this->config->getLog();
            if ($logger) {
                $pdo->setOnLogListener(function ($entry) use ($logger) {
                    $logger->debug('[' . round($entry['time'], 4) . 'sec] ' . $entry['query']);
                });
            }
            $this->config->setDb($pdo);
        } catch (\Exception $e) {
            error_log('<p>' . $e->getMessage() . '</p>');
            exit;
        }

        // * Setup the Template loader, create adapters to look for templates as needed
        /** @var \Dom\Loader $tl */
        $dl = \Dom\Loader::getInstance()->setParams($this->config);
        $dl->addAdapter(new \Dom\Loader\Adapter\DefaultLoader());
        $dl->addAdapter(new \Dom\Loader\Adapter\ClassPath($this->config['template.path'] . '/xtpl'));
        $this->config['dom.loader'] = $dl;
        
        
        // * Dom Node Modifier
        $dm = new \Dom\Modifier\Modifier();
        $dm->add(new \Dom\Modifier\Filter\Path($this->config['site.url']));
        $dm->add(new \Dom\Modifier\Filter\JsLast());
        $this->config['dom.modifier'] = $dm;
        
        
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::INIT => 'onInit');
    }
}
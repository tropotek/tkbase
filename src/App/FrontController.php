<?php
namespace App;

use Tk\Event\Dispatcher;
use Tk\Controller\Resolver;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class FrontController extends \Tk\Kernel\HttpKernel
{
    /**
     * @var null|\Tk\Plugin\Factory
     */
    public $pluginFactory = null;

    /**
     * @var null|\Tk\Mail\Gateway
     */
    public $emailGateway = null;


    /**
     * Constructor.
     *
     * @param Dispatcher $dispatcher
     * @param Resolver $resolver
     * @param $config
     * @throws \Tk\Exception
     */
    public function __construct(Dispatcher $dispatcher, Resolver $resolver, $config)
    {
        parent::__construct($dispatcher, $resolver);

        // Init the plugins
        $this->pluginFactory = $this->getConfig()->getPluginFactory();

        // Initiate the email gateway
        $this->emailGateway = $this->getConfig()->getEmailGateway();
        
        $this->init();
    }

    /**
     * init Application front controller
     *
     * @throws \Tk\Exception
     */
    public function init()
    {
        $logger = $this->getConfig()->getLog();
        
        // Tk Listeners
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\StartupHandler($logger, $this->getConfig()->getRequest(), $this->getConfig()->getSession()));
        $matcher = new \Tk\Routing\UrlMatcher($this->getConfig()->get('site.routes'));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\RouteListener($matcher));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\PageHandler($this->getDispatcher()));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\ResponseHandler($this->getConfig()->getDomModifier()));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionListener($logger));
        if (!$this->getConfig()->isDebug()) {
            $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionEmailListener($this->emailGateway, $logger,
                $this->getConfig()->getSiteEmail(), $this->getConfig()->getSiteTitle()));
        }
        $sh = new \Tk\Listener\ShutdownHandler($logger, $this->getConfig()->getScriptTime());
        $sh->setPageBytes($this->getConfig()->getDomFilterPageBytes());
        $this->getDispatcher()->addSubscriber($sh);

        // App Listeners
        $this->getDispatcher()->addSubscriber(new \App\Listener\AjaxAuthHandler());
        $this->getDispatcher()->addSubscriber(new \App\Listener\AuthHandler());
        $this->getDispatcher()->addSubscriber(new \App\Listener\MasqueradeHandler());
        $this->getDispatcher()->addSubscriber(new \App\Listener\ActionPanelHandler());
        $this->getDispatcher()->addSubscriber(new \App\Listener\PageTemplateHandler());

    }
    
    /**
     * @return \App\Config
     */
    public function getConfig() 
    {
        return \App\Config::getInstance();
    }
    
}
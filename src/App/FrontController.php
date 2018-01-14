<?php
namespace App;

use Tk\Event\Dispatcher;
use Tk\Controller\Resolver;


/**
 * Class FrontController
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class FrontController extends \Tk\Kernel\HttpKernel
{
<<<<<<< HEAD
=======
    /**
     * @var null|\Tk\Plugin\Factory
     */
    public $pluginFactory = null;

    /**
     * @var null|\Tk\Mail\Gateway
     */
    public $emailGateway = null;

>>>>>>> 573c23c28fe7fda9066c66f2276cc1d0f6d44197

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
<<<<<<< HEAD
        $this->getConfig()->getPluginFactory();

        // Initiate the email gateway
        $this->getConfig()->getEmailGateway();

=======
        $this->pluginFactory = $this->getConfig()->getPluginFactory();

        // Initiate the email gateway
        $this->emailGateway = $this->getConfig()->getEmailGateway();
        
>>>>>>> 573c23c28fe7fda9066c66f2276cc1d0f6d44197
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
        /** @var \Tk\Request $request */
        $request = $this->getConfig()->getRequest();

        // Tk Listeners
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\StartupHandler($logger, $request, $this->getConfig()->getSession()));
        $matcher = new \Tk\Routing\UrlMatcher($this->getConfig()->get('site.routes'));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\RouteListener($matcher));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\PageHandler($this->getDispatcher()));
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\ResponseHandler($this->getConfig()->getDomModifier()));
<<<<<<< HEAD

        // Exception Handling
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\LogExceptionListener($logger));
        if (preg_match('|^/ajax/.+|', $request->getUri()->getRelativePath())) { // If ajax request
            $this->getDispatcher()->addSubscriber(new \Tk\Listener\JsonExceptionListener($this->getConfig()->isDebug()));
        } else {
            $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionListener($this->getConfig()->isDebug()));
        }
        if (!$this->getConfig()->isDebug()) {
            $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionEmailListener($this->getConfig()->getEmailGateway(), $logger,
                $this->getConfig()->get('site.email'), $this->getConfig()->get('site.title')));
=======
        $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionListener($logger));
        if (!$this->getConfig()->isDebug()) {
            $this->getDispatcher()->addSubscriber(new \Tk\Listener\ExceptionEmailListener($this->emailGateway, $logger,
                $this->getConfig()->getSiteEmail(), $this->getConfig()->getSiteTitle()));
>>>>>>> 573c23c28fe7fda9066c66f2276cc1d0f6d44197
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
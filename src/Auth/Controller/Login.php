<?php
namespace Auth\Controller;

use Tk\Request;
use Dom\Template;
use Tk\Form;
use Tk\Form\Field;
use Tk\Form\Event;
use Tk\Auth;
use Tk\Auth\Result;

use App\Controller\Iface;

/**
 * Class Index
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Login extends Iface
{

    /**
     * @var Form
     */
    protected $form = null;
    

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct('Login');
    }
    
    /**
     *
     * @param Request $request
     * @return Template
     */
    public function doDefault(Request $request)
    {
        /** @var Auth $auth */
        $auth = $this->getConfig()->getAuth();
        if ($auth && $auth->getIdentity()) {
            \Tk\Uri::create('/admin/index.html')->redirect();
        }

        $this->form = new Form('loginForm');

        $this->form->addField(new Field\Input('username'));
        $this->form->addField(new Field\Password('password'));
        $this->form->addField(new Field\Checkbox('remember'));
        $this->form->addField(new Event\Button('login', array($this, 'doLogin')));
        
        // Find and Fire submit event
        $this->form->execute();

        return $this->show();
    }

    /**
     * show()
     *
     * @return \App\Page\Iface
     */
    public function show()
    {
        $page = new \App\Page\PublicPage($this);
        $template = $this->getTemplate();

        // Render the form
        $ren = new \Tk\Form\Renderer\DomStatic($this->form, $template);
        $ren->show();
        
        return $page->setPageContent($template);
    }


    /**
     * doLogin()
     *
     * @param \Tk\Form $form
     * @throws \Tk\Exception
     */
    public function doLogin($form)
    {
        /** @var Auth $auth */
        $auth = $this->getConfig()->getAuth();

        if (!$form->getFieldValue('username') || !preg_match('/[a-z0-9_ -]{4,32}/i', $form->getFieldValue('username'))) {
            $form->addFieldError('username', 'Please enter a valid username');
        }
        if (!$form->getFieldValue('password') || !preg_match('/[a-z0-9_ -]{4,32}/i', $form->getFieldValue('password'))) {
            $form->addFieldError('password', 'Please enter a valid password');
        }

        $form->addError('TODO: Not implemented Yet!!!!');
        
        if ($form->hasErrors()) {
            return;
        }

        
        $event = new \Auth\Event\LoginEvent($auth, $form->getValues());
        $this->getConfig()->getEventDispatcher()->dispatch('auth.onLogin', $event);
        
        // Use the event to process the login like below....
        $result = $event->getAuth()->getResult();
        
        /*
        $result = null;
        $adapterList = $this->getConfig()->get('system.auth.loginAdapters');
        foreach($adapterList as $name => $class) {
            //vd($form->getFieldValue('username'), $form->getFieldValue('password'));
            $adapter = \App\Helper\Auth::createAdapter($class, $form->getFieldValue('username'), $form->getFieldValue('password'), $this->getConfig());
            $result = $auth->authenticate($adapter);
            $this->getConfig()->getEventDispatcher()->dispatch('auth.onLogin', new \App\EventDispatcher\LoginEvent($auth, $adapter, $result, $form->getValues()));
        }
        */
        
        if (!$result) {
            throw new \Tk\Exception('No valid authentication result received.');
        }

        if ($result->getCode() != Result::SUCCESS) {
            $form->addError( implode("<br/>\n", $result->getMessages()) );
            return;
        }

    }


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        return \Dom\Loader::loadFile($this->getTemplatePath().'/xtpl/login.xtpl');
    }

}
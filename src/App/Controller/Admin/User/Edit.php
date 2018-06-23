<?php
namespace App\Controller\Admin\User;

use Tk\Request;
use Dom\Template;
use Tk\Form;
use Tk\Form\Field;
use Tk\Form\Event;
use App\Controller\AdminIface;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Edit extends AdminIface
{

    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var \App\Db\User
     */
    private $user = null;


    /**
     * @return bool
     */
    public function isProfile() 
    {
        return  (\Tk\Uri::create()->basename() == 'profile.html');
    }

    /**
     *
     * @param Request $request
     * @throws Form\Exception
     * @throws \ReflectionException
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     */
    public function doDefault(Request $request)
    {
        $title = 'User Edit';
        if ($this->isProfile()) {
            $title = 'My Profile';
        }
        $this->setPageTitle($title);
        
        
        $this->user = new \App\Db\User();
        if ($this->isProfile()) {
            $this->user = $this->getUser();
        } else if ($request->get('userId')) {
            $this->user = \App\Db\User::getMapper()->find($request->get('userId'));
        }

        //$this->form = new Form('user-edit');
        $this->form->setEnableRequiredAttr();
        $this->form = \App\Config::getInstance()->createForm('user-edit');
        $this->form->setRenderer(\App\Config::getInstance()->createFormRenderer($this->form));
        
        $this->form->addField(new Field\Input('name'))->setRequired(true)->setTabGroup('Details');
        $this->form->addField(new Field\Input('email'))->setRequired(true)->setTabGroup('Details');

        if ($this->user->isAdmin()) {
            $list = array('Admin' => \App\Db\User::ROLE_ADMIN, 'User' => \App\Db\User::ROLE_USER);
            $this->form->addField(new Field\Select('role', $list))->setTabGroup('Details');
            if (!$this->isProfile()) {
                $this->form->addField(new Field\Checkbox('active'))->setTabGroup('Details');
            }
        }
        
        $this->form->setAttr('autocomplete', 'off');
        $f = $this->form->addField(new Field\Password('newPassword'))->setAttr('placeholder', 'Click to edit')->setAttr('readonly')->setAttr('onfocus', "this.removeAttribute('readonly');this.removeAttribute('placeholder');")->setTabGroup('Password');
        if (!$this->user->getId())
            $f->setRequired(true);
        $f = $this->form->addField(new Field\Password('confPassword'))->setAttr('placeholder', 'Click to edit')->setAttr('readonly')->setAttr('onfocus', "this.removeAttribute('readonly');this.removeAttribute('placeholder');")->setNotes('Change this users password.')->setTabGroup('Password');
        if (!$this->user->getId())
            $f->setRequired(true);


        $this->form->addField(new Event\Submit('update', array($this, 'doSubmit')));
        $this->form->addField(new Event\Submit('save', array($this, 'doSubmit')));
        $this->form->addField(new Event\Link('cancel', \Tk\Uri::create('/admin/userManager.html')));

        $this->form->load(\App\Db\UserMap::create()->unmapForm($this->user));
        
        $this->form->execute();

    }

    /**
     * @param \Tk\Form $form
     * @throws \ReflectionException
     * @throws \Tk\Db\Exception
     */
    public function doSubmit($form)
    {
        // Load the object with data from the form using a helper object
        \App\Db\UserMap::create()->mapForm($form->getValues(), $this->user);

        // Password validation needs to be here
        if ($this->form->getFieldValue('newPassword')) {
            if ($this->form->getFieldValue('newPassword') != $this->form->getFieldValue('confPassword')) {
                $form->addFieldError('newPassword', 'Passwords do not match.');
                $form->addFieldError('confPassword');
            }
        }
        $form->addFieldErrors($this->user->validate());
        
        // Just a small check to ensure the user down not change their own role
        if ($this->user->getId() == $this->getUser()->getId() && $this->user->role != $this->getUser()->role) {
            //\App\Alert::addError('You cannot change your own role information as this will make the system unstable.');
            $form->addError('You cannot change your own role information as this will make the system unstable.');
        }
        if ($this->user->getId() == $this->getUser()->getId() && !$this->user->active) {
            //\App\Alert::addError('You cannot change your own active status as this will make the system unstable.');
            $form->addError('You cannot change your own active status as this will make the system unstable.');
        }
        
        if ($form->hasErrors()) {
            return;
        }

        // Keep the admin account available and working. (hack for basic sites)
        if ($this->user->getId() == 1) {
            $this->user->active = true;
            $this->user->role = \App\Db\User::ROLE_ADMIN;
        }

        $this->user->save();

        \Tk\Alert::addSuccess('Record saved!');
        if ($form->getTriggeredEvent()->getName() == 'update') {
            if ($this->isProfile()) {
                \Tk\Uri::create('/admin/index.html')->redirect();
            }
            \Tk\Uri::create('/admin/userManager.html')->redirect();
        }
        \Tk\Uri::create()->redirect();
    }

    /**
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function show()
    {
        $template = parent::show();
        
        // Render the form
        $template->insertTemplate('form', $this->form->getRenderer()->show());
        
        if ($this->user->id)
            $template->insertText('username', $this->user->name . ' - [ID ' . $this->user->id . ']');
        else
            $template->insertText('username', 'Create User');
        
        return $template;
    }


    /**
     * DomTemplate magic method
     *
     * @return Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <i class="fa fa-user fa-fw"></i> <span var="username"></span>
    </div>
    <div class="panel-body">
        <div var="form"></div>
    </div>
  </div>
    
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }

}
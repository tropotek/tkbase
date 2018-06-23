<?php
namespace App\Controller\Admin\Subscriber;

use Tk\Request;
use Dom\Template;
use Tk\Form;
use Tk\Form\Field;
use Tk\Form\Event;
use App\Controller\AdminIface;

/**
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
     * @var \App\Db\Subscriber
     */
    private $subscriber = null;


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
        $this->setPageTitle('Subscriber Edit');

        
        $this->subscriber = new \App\Db\Subscriber();
        if ($request->get('subscriberId')) {
            $this->subscriber = \App\Db\Subscriber::getMapper()->find($request->get('subscriberId'));
        }

        //$this->form = new Form('subscriber-edit');
        $this->form = \App\Config::getInstance()->createForm('subscriber-edit');
        $this->form->setRenderer(\App\Config::getInstance()->createFormRenderer($this->form));
        
        $this->form->addField(new Field\Input('name'))->setRequired(true);
        $this->form->addField(new Field\Input('email'))->setRequired(true);
        $this->form->addField(new Field\Checkbox('active'));
        $this->form->addField(new Field\Textarea('notes'))->setNotes('Notes only visible to admin users.');


        $this->form->addField(new Event\Submit('update', array($this, 'doSubmit')));
        $this->form->addField(new Event\Submit('save', array($this, 'doSubmit')));
        $this->form->addField(new Event\Link('cancel', \Tk\Uri::create('/admin/subscriberManager.html')));

        $this->form->load(\App\Db\SubscriberMap::create()->unmapForm($this->subscriber));
        
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
        \App\Db\SubscriberMap::create()->mapForm($form->getValues(), $this->subscriber);

        // Password validation needs to be here
        $form->addFieldErrors($this->subscriber->validate());

        if ($form->hasErrors()) {
            return;
        }

        $this->subscriber->save();

        \Tk\Alert::addSuccess('Record saved!');
        if ($form->getTriggeredEvent()->getName() == 'update') {
            \Tk\Uri::create('/admin/subscriberManager.html')->redirect();
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
        
        if ($this->subscriber->id)
            $template->insertText('username', $this->subscriber->name . ' - [ID ' . $this->subscriber->id . ']');
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
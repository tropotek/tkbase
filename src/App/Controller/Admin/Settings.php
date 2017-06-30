<?php
namespace App\Controller\Admin;

use Tk\Request;
use Tk\Form;
use Tk\Form\Event;
use Tk\Form\Field;
use \App\Controller\Iface;

/**
 * Class Contact
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Settings extends Iface
{

    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var \Tk\Db\Data
     */
    protected $data = null;

    

    /**
     * doDefault
     *
     * @param Request $request
     * @return \App\Page\PublicPage
     */
    public function doDefault(Request $request)
    {
        $this->setPageTitle('Settings');
        $this->data = \Tk\Db\Data::create();
        
        $this->form = Form::create('formEdit');

        $this->form->addField(new Field\Input('site.title'))->setLabel('Site Title')->setRequired(true);
        $this->form->addField(new Field\Input('site.email'))->setLabel('Site Email')->setRequired(true);
        $this->form->addField(new Field\Checkbox('site.client.registration'))->setLabel('Client Registration')->setNotes('Allow users to create new accounts');
        $this->form->addField(new Field\Checkbox('site.client.activation'))->setLabel('Client Activation')->setNotes('Allow users to activate their own accounts');

        $this->form->addField(new Event\Button('update', array($this, 'doSubmit')));
        $this->form->addField(new Event\Button('save', array($this, 'doSubmit')));
        $this->form->addField(new Event\LinkButton('cancel', \Tk\Uri::create('/admin/index.html')));

        $this->form->load($this->data->toArray());
        $this->form->execute();

    }

    /**
     * doSubmit()
     *
     * @param Form $form
     */
    public function doSubmit($form)
    {
        $values = $form->getValues();
        $this->data->replace($values);
        
        if (empty($values['site.title']) || strlen($values['site.title']) < 3) {
            $form->addFieldError('site.title', 'Please enter your name');
        }
        if (empty($values['site.email']) || !filter_var($values['site.email'], \FILTER_VALIDATE_EMAIL)) {
            $form->addFieldError('site.email', 'Please enter a valid email address');
        }
        
        if ($this->form->hasErrors()) {
            return;
        }
        
        $this->data->save();
        
        \Tk\Alert::addSuccess('Site settings saved.');
        if ($form->getTriggeredEvent()->getName() == 'update') {
            \Tk\Uri::create('/admin/index.html')->redirect();
        }
        \Tk\Uri::create()->redirect();
    }

    /**
     * show()
     *
     * @return \App\Page\Iface
     */
    public function show()
    {
        $template = parent::show();
        
        // Render the form
        $fren = new \Tk\Form\Renderer\Dom($this->form);
        $template->insertTemplate($this->form->getId(), $fren->show()->getTemplate());

        return $template;
    }

    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <i class="fa fa-cogs fa-fw"></i> Actions
    </div>
    <div class="panel-body ">
      <div class="row">
        <div class="col-lg-12">
          <a href="javascript: window.history.back();" class="btn btn-default"><i class="fa fa-arrow-left"></i> <span>Back</span></a>
          <a href="/admin/plugins.html" class="btn btn-default"><i class="fa fa-plug"></i> <span>Plugins</span></a>
        </div>
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <i class="glyphicon glyphicon-cog"></i> Site Settings
    </div>
    <div class="panel-body">
      <div var="formEdit"></div>
    </div>
  </div>

</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }
}
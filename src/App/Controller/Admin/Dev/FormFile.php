<?php
namespace App\Controller\Admin\Dev;

use App\Form\Field\FileUpload\FileUpload;
use Tk\Form\Event;
use Tk\Form\Field;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class FormFile extends \Bs\Controller\AdminIface
{

    /**
     * @var \Tk\Form
     */
    protected $form1 = null;

    /**
     * @var \Tk\Form
     */
    protected $form2 = null;


    /**
     */
    public function __construct()
    {
        $this->setPageTitle('Form Files');
        //$this->getActionPanel()->setEnabled(false);
    }

    protected function createForm($id)
    {
        $form = $this->getConfig()->createForm($id);
        return $form;
    }

    /**
     *
     * @param \Tk\Request $request
     * @throws \Exception
     */
    public function doDefault(\Tk\Request $request)
    {

        $this->form1 = $this->createForm('form1');
        $this->form1->appendField(new Field\File('DefaultField'));
        $this->form1->appendField(new Event\Submit('update', array($this, 'doSubmit')));
        $this->form1->appendField(new Event\Submit('save', array($this, 'doSubmit')));
        $this->form1->appendField(new Event\Link('cancel', $this->getBackUrl()));
        $this->form1->execute();


        $this->form2 = $this->createForm('form2');

        $this->form2->appendField(new FileUpload('fileupload'));

        $this->form2->appendField(new Event\Submit('update', array($this, 'doSubmit')));
        $this->form2->appendField(new Event\Submit('save', array($this, 'doSubmit')));
        $this->form2->appendField(new Event\Link('cancel', $this->getBackUrl()));
        $this->form2->execute();


    }

    /**
     * @param \Tk\Form $form
     * @param \Tk\Form\Event\Iface $event
     * @throws \Exception
     */
    public function doSubmit($form, $event)
    {
        if ($form->getId() != 'form2') return;

        // Load the object with data from the form using a helper object
        //$this->getConfig()->getSubjectMapper()->mapForm($form->getValues(), $this->subject);
        //$form->addFieldErrors($this->subject->validate());


        if ($form->hasErrors()) {
            return;
        }



        \Tk\Alert::addSuccess('Record saved!');
        $event->setRedirect($this->getConfig()->getBackUrl());
        if ($form->getTriggeredEvent()->getName() == 'save') {
            $event->setRedirect(\Tk\Uri::create());
        }
    }

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();

        $template->appendTemplate('form1', $this->form1->getRenderer()->show());
        $template->appendTemplate('form2', $this->form2->getRenderer()->show());

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
<div class="">

  <div class="tk-panel" data-panel-title="Default File Field #1" data-panel-icon="fa fa-form" var="form1"></div>
  <div class="tk-panel" data-panel-title="jQuery-File-Upload" data-panel-icon="fa fa-form" var="form2"></div>
  <div class="tk-panel" data-panel-title="SQL Required" data-panel-icon="fa fa-form" var="sql">
<pre>  
CREATE TABLE IF NOT EXISTS file
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) UNSIGNED NOT NULL DEFAULT 0,
    fkey VARCHAR(64) DEFAULT '' NOT NULL,
    fid INT DEFAULT 0 NOT NULL,
    label VARCHAR(128) default '',
    path TEXT NULL,
    bytes INT DEFAULT 0 NOT NULL,
    mime VARCHAR(255) DEFAULT '' NOT NULL,
    active TINYINT(1) default 0,
    notes TEXT NULL,
    hash VARCHAR(128) DEFAULT '' NOT NULL,
    modified datetime NOT NULL,
    created datetime NOT NULL,
    KEY user_id (user_id),
    KEY fkey (fkey),
    KEY fkey_2 (fkey, fid)
);
</pre>
  </div>
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }


}
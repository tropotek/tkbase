<?php
namespace App\Controller\Admin\Dev;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Form extends \Bs\Controller\AdminIface
{

    /**
     * @var \Tk\Form
     */
    protected $form = null;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setPageTitle('Multiple File upload');
        $this->getCrumbs()->reset();
    }

    /**
     *
     * @param Request $request
     * @throws \Tk\Exception
     * @throws \Tk\Form\Exception
     */
    public function doDefault(Request $request)
    {

        $this->form = new \Tk\Form('formEdit');

        $this->form->addField(new \Tk\Form\Field\Input('name'));
        $this->form->addField(new \Tk\Form\Field\Input('email'));
        $this->form->addField(new \Tk\Form\Field\File('raw', $this->getConfig()->getDataPath().'/testFile1'))
            ->addCss('tk-no-fileinput');
        $this->form->addField(new \Tk\Form\Field\File('file', $this->getConfig()->getDataPath().'/testFile2'))
            ->addCss('tk-fileinput');
        $this->form->addField(new \Tk\Form\Field\File('image', $this->getConfig()->getDataPath().'/testFile2'))
            ->addCss('tk-imageinput');
        $this->form->addField(new \Tk\Form\Field\File('attach[]', $this->getConfig()->getDataPath().'/testFile3'))
            ->addCss('tk-multiinput');

        $this->form->addField(new \Tk\Form\Event\Submit('update', array($this, 'doSubmit')));
        $this->form->addField(new \Tk\Form\Event\Submit('save', array($this, 'doSubmit')));
        $this->form->addField(new \Tk\Form\Event\Link('cancel', \Tk\Uri::create('/admin/index.html')));

        //$this->form->load(\Bs\Db\UserMap::create()->unmapForm($this->user));

        $this->form->execute();

    }

    /**
     * @param \Tk\Form $form
     */
    public function doSubmit($form)
    {
        // Load the object with data from the form using a helper object
        //\Bs\Db\UserMap::create()->mapForm($form->getValues(), $this->user);

        if ($form->hasErrors()) {
            return;
        }

        //$this->user->save();

        \Tk\Alert::addSuccess('User record saved!');
        if ($form->getTriggeredEvent()->getName() == 'update') {
                \Tk\Uri::create('/admin/index.html')->redirect();
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
        $fren = new \Tk\Form\Renderer\Dom($this->form);
        $template->insertTemplate('form', $fren->show());

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
      <i class="fa fa-empire fa-fw"></i> File Upload
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
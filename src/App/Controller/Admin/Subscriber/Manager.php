<?php
namespace App\Controller\Admin\Subscriber;

use Tk\Request;
use Dom\Template;
use Tk\Form\Field;
use App\Controller\AdminIface;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Manager extends AdminIface
{

    /**
     * @var \Tk\Table
     */
    protected $table = null;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPageTitle('Subscribers');
        $this->getCrumbs()->reset();
    }

    /**
     * @param Request $request
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     * @throws \Tk\Form\Exception
     */
    public function doDefault(Request $request)
    {
        
        //$this->table = new \Tk\Table('subscriber-list');
        $this->table = \App\Config::getInstance()->createTable('subscriber-list');
        $this->table->setRenderer(\App\Config::getInstance()->createTableRenderer($this->table));

        $this->table->addCell(new \Tk\Table\Cell\Checkbox('id'));
        $this->table->addCell(new \Tk\Table\Cell\Text('name'))->addCss('key')->setUrl(\Tk\Uri::create('admin/subscriberEdit.html'));
        $this->table->addCell(new \Tk\Table\Cell\Text('email'));
        $this->table->addCell(new \Tk\Table\Cell\Boolean('active'));
        $this->table->addCell(new \Tk\Table\Cell\Date('created'));

        // Filters
        $this->table->addFilter(new Field\Input('keywords'))->setLabel('')->setAttr('placeholder', 'Keywords');

        // Actions
        $this->table->addAction(new \Tk\Table\Action\Csv($this->getConfig()->getDb()));
        $this->table->addAction(new \Tk\Table\Action\Delete());

        $list = \App\Db\SubscriberMap::create()->findFiltered($this->table->getFilterValues(), $this->table->getTool('a.name'));
        $this->table->setList($list);

    }

    /**
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function show()
    {
        $template = parent::show();

        $this->getActionPanel()->add(\Tk\Ui\Button::create('Add Subscriber', \Tk\Uri::create('/admin/subscriberEdit.html'), 'fa fa-newspaper-o'));

        $template->replaceTemplate('table', $this->table->getRenderer()->show());
        
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
      <i class="fa fa-newspaper-o fa-fw"></i> Subscribers
    </div>
    <div class="panel-body">
      <div var="table"></div>
    </div>
  </div>

</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }


}
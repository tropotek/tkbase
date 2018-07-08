<?php
namespace App\Controller\Admin\Event;

use Tk\Request;
use Dom\Template;
use Tk\Form\Field;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Manager extends \Bs\Controller\AdminIface
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
        $this->setPageTitle('Events');
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
        
        //$this->table = new \Tk\Table('event-list');
        $this->table = \App\Config::getInstance()->createTable('event-list');
        $this->table->setRenderer(\App\Config::getInstance()->createTableRenderer($this->table));

        $this->table->addCell(new \Tk\Table\Cell\Checkbox('id'));
        $this->table->addCell(new \Tk\Table\Cell\Text('title'))->addCss('key')->setUrl(\Tk\Uri::create('/admin/eventEdit.html'));
        $this->table->addCell(new \Tk\Table\Cell\Text('city'));
        $this->table->addCell(new \Tk\Table\Cell\Text('state'));
        $this->table->addCell(new \Tk\Table\Cell\Text('country'));

        $this->table->addCell(new \Tk\Table\Cell\Date('dateStart'))->setOnPropertyValue(function ($cell, $obj, $value) {
            /** @var \Tk\Table\Cell\Date $cell */
            /** @var \App\Db\Event $obj */

            $now = \Tk\Date::create();
            if (\Tk\Date::greaterThan($now, $obj->dateEnd)) {
                $cell->getRow()->addCss('disabled');
            }

            return $value;
        });
        $this->table->addCell(new \Tk\Table\Cell\Boolean('active'));
        $this->table->addCell(new \Tk\Table\Cell\Date('created'));

        // Filters
        $this->table->addFilter(new Field\Input('keywords'))->setLabel('')->setAttr('placeholder', 'Keywords');

        // Actions
        $this->table->addAction(new \Tk\Table\Action\Csv($this->getConfig()->getDb()));
        $this->table->addAction(new \Tk\Table\Action\Delete());

        //$this->table->resetSessionTool();
        $list = \App\Db\EventMap::create()->findFiltered($this->table->getFilterValues(), $this->table->getTool('a.dateStart DESC'));
        $this->table->setList($list);

    }

    /**
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function show()
    {
        $template = parent::show();

        $this->getActionPanel()->add(\Tk\Ui\Button::create('Add Event', \Tk\Uri::create('/admin/eventEdit.html'), 'fa fa-calendar'));

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
      <i class="fa fa-calendar fa-fw"></i> Events
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
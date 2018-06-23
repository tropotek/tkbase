<?php
namespace App\Controller\Admin\User;

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
        $this->setPageTitle('User Manager');
        $this->getCrumbs()->reset();
    }


    /**
     *
     * @param Request $request
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     * @throws \Tk\Form\Exception
     * @throws \Exception
     */
    public function doDefault(Request $request)
    {
        //$this->table = new \Tk\Table('tableOne');
        $this->table = \App\Config::getInstance()->createTable('user-list');
        $this->table->setRenderer(\App\Config::getInstance()->createTableRenderer($this->table));

        $this->table->addCell(new \Tk\Table\Cell\Checkbox('id'));
        $this->table->addCell(new ActionsCell('action'));
        $this->table->addCell(new \Tk\Table\Cell\Text('name'))->addCss('key')->setUrl(\Tk\Uri::create('admin/userEdit.html'));
        $this->table->addCell(new \Tk\Table\Cell\Text('username'));
        $this->table->addCell(new \Tk\Table\Cell\Text('email'));
        $this->table->addCell(new \Tk\Table\Cell\Text('role'));
        $this->table->addCell(new \Tk\Table\Cell\Boolean('active'));
        $this->table->addCell(new \Tk\Table\Cell\Date('created'));

        // Filters
        $this->table->addFilter(new Field\Input('keywords'))->setLabel('')->setAttr('placeholder', 'Keywords');

        // Actions
        $this->table->addAction(new \Tk\Table\Action\Csv($this->getConfig()->getDb()));
        $this->table->addAction(new \Tk\Table\Action\Delete())->setExcludeList(array('1'));

        $users = \App\Db\UserMap::create()->findFiltered($this->table->getFilterValues(), $this->table->getTool('a.name'));
        $this->table->setList($users);

    }

    /**
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function show()
    {
        $template = parent::show();

        $this->getActionPanel()->add(\Tk\Ui\Button::create('Add User', \Tk\Uri::create('/admin/userEdit.html'), 'fa fa-user'));

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
      <i class="fa fa-users fa-fw"></i> Users
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

class ActionsCell extends \Tk\Table\Cell\Iface
{
    public function __construct($property, $label = null)
    {
        parent::__construct($property, $label);
        $this->setOrderProperty('');
    }

    /**
     * @param \App\Db\User $obj
     * @param int|null $rowIdx The current row being rendered (0-n) If null no rowIdx available.
     * @return string
     */
    public function getCellHtml($obj, $rowIdx = null)
    {
        $this->addCss('text-center');
        $name = $obj->name;
        $url = htmlentities(\Tk\Uri::create()->set(\App\Listener\MasqueradeHandler::MSQ, $obj->id)->toString());

        $disable = '';
        if (\App\Config::getInstance()->getUser()->id == $obj->id) {
            $disable = 'disabled="disabled"';
        }

        $html = <<<HTML
<span>
  <a href="$url" class="btn btn-xs btn-default" title="Masquerade As `$name`" $disable><i class="fa fa-user-secret"></i></a>
</span>
HTML;

        return $html;
    }

}
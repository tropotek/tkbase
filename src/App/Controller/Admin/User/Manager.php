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
     *
     * @param Request $request
     */
    public function doDefault(Request $request)
    {
        $this->setPageTitle('User Manager');
        
        $this->table = new \Tk\Table('tableOne');

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
        $this->table->addAction(\Tk\Table\Action\Button::create('New User', 'fa fa-plus', \Tk\Uri::create('admin/userEdit.html')));
        $this->table->addAction(new \Tk\Table\Action\Delete());
        $this->table->addAction(new \Tk\Table\Action\Csv($this->getConfig()->getDb()));

        $users = \App\Db\UserMap::create()->findFiltered($this->table->getFilterValues(), $this->table->makeDbTool('a.name'));
        $this->table->setList($users);

    }

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();
        
        $ren =  \Tk\Table\Renderer\Dom\Table::create($this->table);
        $ren->show();
        $template->replaceTemplate('table', $ren->getTemplate());
        
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
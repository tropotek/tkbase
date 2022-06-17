<?php
namespace App\Controller\Admin;

use Bs\Db\User;
use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Dashboard extends \Bs\Controller\AdminIface
{

    /**
     * @var \Bs\Table\User
     */
    protected $table = null;



    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setPageTitle('Dashboard');
        $this->getActionPanel()->setEnabled(false);
        $this->getCrumbs()->reset();
    }


    /**
     * @param Request $request
     * @throws \Exception
     */
    public function doDefault(Request $request)
    {
        $editUrl = \Bs\Uri::createHomeUrl('/memberEdit.html');

        $this->table = \Bs\Table\User::create()->setEditUrl($editUrl);
        $this->table->setTargetType(User::TYPE_MEMBER);
        $this->table->init();
        $this->table->setList($this->table->findList(array('type' => User::TYPE_MEMBER)));

    }


    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();

        $template->appendTemplate('panel', $this->table->show());

        return $template;
    }

    /**
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div>
  <div class="tk-panel" data-panel-title="Site Members" data-panel-icon="fa fa-empire" var="panel"></div>
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }

}

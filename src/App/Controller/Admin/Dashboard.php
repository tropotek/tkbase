<?php
namespace App\Controller\Admin;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Dashboard extends \Bs\Controller\AdminIface
{

    protected $menu = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPageTitle('Dashboard');
        $this->getActionPanel()->setEnabled(false);
        $this->getCrumbs()->reset();
    }


    /**
     * @param Request $request
     */
    public function doDefault(Request $request)
    {

        // The menu tester
//
//        $this->menu = \Tk\Ui\Menu\Menu::create('tk-navigation');
//
//        $this->menu->append(\Tk\Ui\Menu\Item::create('Item 1', \Tk\Uri::create('/item1.html'), 'fa fa-cogs'));
//        $this->menu->append(\Tk\Ui\Menu\Item::create('Item 2', \Tk\Uri::create('/item2.html'), 'fa fa-user'));
//
//        $sub1 = $this->menu->append(\Tk\Ui\Menu\Item::create('Item 3', \Tk\Uri::create('/item3.html'), 'fa fa-user-md'));
//        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.1', \Tk\Uri::create('/item3-1.html'), 'fa fa-list'));
//        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.2', \Tk\Uri::create('/item3-2.html'), 'fa fa-home'))
//            ->append(\Tk\Ui\Menu\Item::create('Item 3.2.1', \Tk\Uri::create('/item3-2-1.html'), 'fa fa-arrow-left'));
//        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.3', null, 'fa fa-globe'))
//            ->append(\Tk\Ui\Menu\Item::create('Item 3.3.1', \Tk\Uri::create('/item3-3-1.html'), 'fa fa-github'));
//
//        $this->menu->append(\Tk\Ui\Menu\Item::create('Item 4', \Tk\Uri::create('/item4.html'), 'fa fa-building'));
//        $this->menu->append(\Tk\Ui\Menu\Item::create('Item 5', \Tk\Uri::create('/item5.html'), 'fa fa-empire'));



    }

    public function show()
    {
        $template = parent::show();

//        vd($menu->__toString());
//        $template->appendTemplate('menu-test', \Tk\Ui\Menu\ListRenderer::create($this->menu)->show());


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
<p var="menu-test"></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }
    
}
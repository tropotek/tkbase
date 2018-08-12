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
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPageTitle('Dashboard');
        $this->getCrumbs()->reset();
    }


    /**
     * @param Request $request
     */
    public function doDefault(Request $request)
    {
        $this->getActionPanel()->setEnabled(false);


        // The menu tester

        $menu = \Tk\Ui\Menu\Menu::create('Navigation');

        $menu->append(\Tk\Ui\Menu\Item::create('Item 1', \Tk\Uri::create('/item1.html'), 'fa fa-cogs'));
        $menu->append(\Tk\Ui\Menu\Item::create('Item 2', \Tk\Uri::create('/item2.html'), 'fa fa-user'));

        $sub1 = $menu->append(\Tk\Ui\Menu\Item::create('Item 3', \Tk\Uri::create('/item3.html'), 'fa fa-user-md'));
        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.1', \Tk\Uri::create('/item3-1.html'), 'fa fa-list'));
        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.2', \Tk\Uri::create('/item3-2.html'), 'fa fa-home'));
        $sub1->append(\Tk\Ui\Menu\Item::create('Item 3.3', \Tk\Uri::create('/item3-3.html'), 'fa fa-globe'));

        $menu->append(\Tk\Ui\Menu\Item::create('Item 4', \Tk\Uri::create('/item4.html'), 'fa fa-building'));
        $menu->append(\Tk\Ui\Menu\Item::create('Item 5', \Tk\Uri::create('/item5.html'), 'fa fa-empire'));

        // append and prepend

        // Sub menus

        vd($menu->__toString());
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
<p>&nbsp;</p>
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
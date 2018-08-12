<?php
namespace App\Ui\Menu;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class AdminActionsNav extends Iface
{


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<li class="dropdown d-none d-md-block">
  <a class="btn dropdown-toggle" data-toggle="dropdown">New Actions</a>
  <ul class="dropdown-menu" var="actions-nav">
    <li class="item"><a href="#"><i class="fa fa-tasks"></i> New Task</a></li>
    <li class="item"><a href="#"><i class="fa fa-folder-open"></i> New Project</a></li>
    <li class="item"><a href="#"><i class="fa fa-shopping-cart"></i> New Product</a></li>
    <li class="item"><a href="#"><i class="fa fa-building-o"></i> New Client</a></li>
  </ul>
</li>
HTML;
        return \Dom\Loader::load($xhtml);
    }
}

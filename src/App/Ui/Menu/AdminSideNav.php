<?php
namespace App\Ui\Menu;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class AdminSideNav extends Iface
{


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<ul class="nav" id="side-menu" var="nav">
  <!--<li class="sidebar-search">-->
    <!--<div class="input-group custom-search-form">-->
      <!--<input type="text" class="form-control input-sm" placeholder="Search..." />-->
      <!--<span class="input-group-btn">-->
        <!--<button class="btn btn-default btn-sm" type="button">-->
          <!--<i class="fa fa-search"></i>-->
        <!--</button>-->
      <!--</span>-->
    <!--</div>-->
  <!--</li>-->
  
  <li><a href="/admin/index.html"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
  
  <li><a href="#"><i class="fa fa-cogs fa-fw"></i> System<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level" var="system-nav">
      <li><a href="/admin/settings.html"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
       <li><a href="/admin/userManager.html"><i class="fa fa-users fa-fw"></i> Users</a></li>
    </ul>
  </li>
  <li><a href="#"><i class="fa fa-globe fa-fw"></i> Site<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level" var="app-nav">
      <li><a href="/index.html" target="_blank"><i class="fa fa-home fa-fw"></i> View Site</a></li>
    </ul>
  </li>
  <li choice="debug"><a href="#"><i class="fa fa-bug fa-fw"></i> Development<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level" var="debug-nav">
      <li choice="debug"><a href="/admin/dev/events.html"><i class="fa fa-empire fa-fw"></i> Events</a></li>
    </ul>
  </li>
  
</ul>
HTML;

        return \Dom\Loader::load($xhtml);
    }
}

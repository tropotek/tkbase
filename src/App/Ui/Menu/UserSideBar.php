<?php
namespace App\Ui\Menu;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class UserSideBar extends Iface
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
  <li><a href="/user/index.html"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
  
  <li><a href="#"><i class="fa fa-cogs fa-fw"></i> System<span class="fa arrow"></span></a>
    <ul class="nav nav-second-level" var="system-menu">
      <li><a href="/user/settings.html"><i class="fa fa-user fa-fw"></i> My Profile</a></li>
    </ul>
  </li>
  <!--<li><a href="#"><i class="fa fa-globe fa-fw"></i> Site<span class="fa arrow"></span></a>-->
    <!--<ul class="nav nav-second-level" var="app-menu">-->
      <!--<li><a href="/index.html" target="_blank"><i class="fa fa-home fa-fw"></i> View Site</a></li>-->
    <!--</ul>-->
  <!--</li>-->
  
</ul>
HTML;

        return \Dom\Loader::load($xhtml);
    }

}
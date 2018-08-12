<?php
namespace App\Ui\Menu;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class AdminDropdownNav extends Iface
{


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<ul class="dropdown-menu dropdown-menu-right" var="user-menu">
  <li class="item"><a href="/index.html" target="_blank"><i class="fa fa-home"></i>View Site</a></li>
  <li class="item"><a href="/admin/profile.html"><i class="fa fa-user"></i>Profile</a></li>
  <li class="item"><a href="/admin/settings.html"><i class="fa fa-cog"></i>Settings</a></li>
  <!--<li class="item"><a href="#"><i class="fa fa-envelope-o"></i>Messages</a></li>-->
  <li class="item"><a href="#" data-toggle="modal" data-target="#aboutModal"><i class="fa fa-info-circle"></i>About</a></li>
  <li class="dropdown-divider"></li>
  <li class="item"><a href="#" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-power-off"></i>Logout</a></li>
</ul>
HTML;
        return \Dom\Loader::load($xhtml);
    }
}

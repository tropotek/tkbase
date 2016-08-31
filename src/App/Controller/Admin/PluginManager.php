<?php
namespace App\Controller\Admin;

use Tk\Request;
use Dom\Template;
use App\Controller\Iface;
use Tk\Plugin\Factory;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class PluginManager extends Iface
{

    /**
     * @var Factory
     */
    protected $pluginFactory = null;
    

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Plugin Manager');
    }

    /**
     *
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doDefault(Request $request)
    {
        $this->pluginFactory = Factory::getInstance($this->getConfig());

        return $this->show();
    }

    /**
     * @return \App\Page\Iface
     */
    public function show()
    {
        $template = $this->getTemplate();

//        $list = $this->getConfig()->getPluginFactory()->getAvailablePlugins();
//        foreach ($list as $pluginName) {
//            $repeat = $template->getRepeat('row');
//            $repeat->insertText('title', $pluginName);
//            $repeat->setAttr('icon', 'src', $this->getConfig()->getPluginFactory()->getPluginUrl().'/'.$pluginName.'/icon.png');
//            if ($this->getConfig()->getPluginFactory()->isActive($pluginName)) {
//                $plugin = $this->getConfig()->getPluginFactory()->getPlugin($pluginName);
//
//                $repeat->setChoice('active');
//                if ($plugin) {
//                    $repeat->setAttr('cfg', 'href', \Tk\Url::create($plugin->getConfigUrl()));
//                    $repeat->setAttr('title', 'href', \Tk\Url::create($plugin->getConfigUrl()));
//                }
//                $repeat->setAttr('deact', 'href', $this->getUri()->reset()->set('deact', $pluginName));
//            } else {
//                $repeat->setChoice('inactive');
//                $repeat->setAttr('act', 'href', $this->getUri()->reset()->set('act', $pluginName));
//                $repeat->setAttr('del', 'href', $this->getUri()->reset()->set('del', $pluginName));
//            }
//
//            $info = $this->getConfig()->getPluginFactory()->getPluginInfo($pluginName);
//            if ($info) {
//                if ($info->version) {
//                    $repeat->insertText('version', $info->version);
//                    $repeat->setChoice('version');
//                }
//                $repeat->insertText('name', $info->name);
//                $repeat->insertText('desc', $info->description);
//                $repeat->insertText('author', $info->authors[0]->name);
//                $repeat->setAttr('www', 'href', $info->homepage);
//                $repeat->insertText('www', $info->homepage);
//                $repeat->setChoice('info');
//            } else {
//                $repeat->insertText('desc', 'Err: No metadata file found!');
//            }
//
//
//            $repeat->appendRepeat();
//        }

        $js = <<<JS
jQuery(function ($) {
    $('.act').click(function (e) {
        return confirm('Are you sure you want to insstall this plugin?');
    });
    $('.del').click(function (e) {
        return confirm('Are you sure you want to delete this plugin?');
    });
    $('.deact').click(function (e) {
        return confirm('Are you sure you want to uninstall this plugin?');
    });
});
JS;
        $template->appendJs($js);
        
        return $this->getPage()->setPageContent($template);
    }

    /**
     * DomTemplate magic method
     *
     * @return Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<XHTML
<div class="row">
  <div class="col-md-8 col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Available Plugins</h3>
      </div>
      <div class="panel-body">

        <ul class="list-group">
          <li class="list-group-item" repeat="row">
            <div class="row">
              <div class="col-xs-2 col-md-1">
                <img class="media-object" src="#" var="icon" style="width: 100%; " />
              </div>
              <div class="col-xs-10 col-md-11">
                <div>
                  <h4><a href="#" var="title"></a></h4>
                  <p choice="info">
                    <small choice="version"><strong>Version:</strong> <span var="version"></span></small> <br choice="version" />
                    <small><strong>Package:</strong> <span var="name"></span></small> <br/>
                    <small><strong>Author:</strong> <span var="author"></span></small> <br />
                    <small><strong>Homepage:</strong> <a href="#" var="www" target="_blank">View Website</a></small>
                  </p>
                </div>
                <p class="comment-text" var="desc"></p>
                <div class="action">
                  <a href="#" class="btn btn-primary btn-xs noblock act" choice="inactive" var="act"><i class="glyphicon glyphicon-log-in"></i> Install</a>
                  <a href="#" class="btn btn-danger btn-xs noblock del" choice="inactive" var="del"><i class="glyphicon glyphicon-remove-circle"></i> Delete</a>
                  <a href="#" class="btn btn-warning btn-xs noblock deact" choice="active" var="deact"><i class="glyphicon glyphicon-log-out"></i> Uninstall</a>
                  <a href="#" class="btn btn-success btn-xs" choice="active" var="cfg"><i class="glyphicon glyphicon-edit"></i> Config</a>
                </div>
              </div>
            </div>
          </li>
        </ul>

      </div>
    </div>
  </div>

  <div class="col-md-4 col-sm-12">
    <div class="panel panel-default" id="uploadForm">
      <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-log-out"></span> Upload Plugin</h3>
      </div>
      <div class="panel-body">
        <p>Select A zip/tgz plugin package to upload.</p>
        <div var="UploadPlugin"></div>
      </div>
    </div>
  </div>

</div>
XHTML;

        return \Dom\Loader::load($xhtml);
    }


}
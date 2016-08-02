<?php
namespace App\Controller\User;

use Tk\Request;
use App\Controller\Iface;

/**
 * Class Index
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Index extends Iface
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('My Account');
    }

    /**
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doDefault(Request $request)
    {
        // TODO:

        return $this->show();
    }



    public function show()
    {
        $template = $this->getTemplate();
        
        $template->insertText('name', $this->getUser()->name);
        
        
        
        return $this->getPage()->setPageContent($template);
    }


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<XHTML
<div class="container">
<div class="row">

  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> Welcome <span var="name"></span>
      </div>
      <div class="panel-body ">

        <p>Something spiffy.....</p>

      </div>
    </div>
  </div>

</div>
</div>
XHTML;

        return \Dom\Loader::load($xhtml);
    }

}
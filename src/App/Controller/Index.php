<?php
namespace App\Controller;

use Composer\Autoload\ClassLoader;
use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Index extends \Bs\Controller\Index
{

    /**
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPageTitle('Home');
    }

    /**
     * @param Request $request
     */
    public function doDefault(Request $request)
    {
        parent::doDefault($request);

        /** @var ClassLoader $c */
        $c = $this->getConfig()->get('composer');
        vd($c->getPrefixes());
    }

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();

        return $template;
    }

    /**
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div class="tpl-index"></div>
HTML;
        return \Dom\Loader::load($xhtml);
    }

}

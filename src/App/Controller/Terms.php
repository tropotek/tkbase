<?php
namespace App\Controller;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Terms extends \Bs\Controller\Index
{

    /**
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPageTitle('Terms And Conditions');
    }

    /**
     * @param Request $request
     */
    public function doDefault(Request $request)
    {
        parent::doDefault($request);
    }

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();

        return $template;
    }

}

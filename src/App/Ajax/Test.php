<?php
namespace App\Ajax;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Test
{


    /**
     * GET
     * http://dev.ttek.org/~godar/Projects/tk2base/api/1.0/testOne
     * 
     * 
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doTestOne(Request $request)
    {
        $status = 200;
        $data = array('msg' => 'This is a testOne');
        
        vd('Ajax Test: ' . $request->getMethod());
        vd($request->all());
        
        return \Tk\ResponseJson::createJson($data, $status);

    }

    /**
     * POST
     * http://dev.ttek.org/~godar/Projects/tk2base/api/1.0/testTwo
     *
     *
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doTestTwo(Request $request)
    {
        $status = 200;
        $data = array('msg' => 'This is a testTwo');

        vd('Ajax Test: ' . $request->getMethod());

        vd(file_get_contents("php://input"));

        return \Tk\ResponseJson::createJson($data, $status);

    }


}
<?php
namespace App\Ajax;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class User
{


    /**
     * POST
     * http://dev.ttek.org/~godar/Projects/tk2base/api/1.0/findUser
     * 
     * 
     * @param Request $request
     * @return \Tk\ResponseJson
     */
    public function doFindUser(Request $request)
    {
        $status = 200;
        //$raw = json_decode($request->getRawPostData());
        //$data = array('msg' => 'This is a testOne');
//        vd('FindUser: ' . $request->getMethod());
//        vd($request->getRawPostData());
//        vd($raw);
        vd($request->all());
        $data = \App\Db\UserMap::create()->findFiltered($request->all())->toArray();
        vd($data);
        
        return \Tk\ResponseJson::createJson($data, $status);

    }

    /**
     * POST
     * http://dev.ttek.org/~godar/Projects/tk2base/api/1.0/testTwo
     *
     *
     * @param Request $request
     * @return \Tk\ResponseJson
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
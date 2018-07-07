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
     * @param Request $request
     * @return \Tk\ResponseJson
     * @throws \Tk\Db\Exception
     */
    public function doFindUser(Request $request)
    {
        $status = 200;

        $data = \App\Db\UserMap::create()->findFiltered($request->all())->toArray();
        
        return \Tk\ResponseJson::createJson($data, $status);

    }



}
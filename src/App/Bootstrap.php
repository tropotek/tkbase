<?php
namespace App;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Bootstrap extends \Bs\Bootstrap
{

    /**
     * @return \Bs\Bootstrap
     * @throws \Exception
     */
    public static function execute()
    {
        $config = parent::execute();



        return $config;
    }

}



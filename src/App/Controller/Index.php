<?php
/**
 * Created by PhpStorm.
 *
 * @date 16-05-2016
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
namespace App\Controller;
use Tk\Response;

/** */
class Index
{

    
    public function __construct()
    {
        
    }
    
    public function doDefault($request)
    {
        $response = new Response();
        $response->setBody('<h1>YAHOOO</h1><p>Could this controller be working?</p>');
        
        
        
        
        throw new \Exception('Haha I got Excepted........lol');
        
        
        
        
        
        return $response;
    }
    
}
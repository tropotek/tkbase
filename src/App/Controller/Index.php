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
        $config = \Tk\Config::getInstance();
//        $response = new Response();
//        $response->setBody('<h1>YAHOOO</h1><p>Could this controller be working?</p>');
//        return $response;
        
//        throw new \Exception('Haha I got Excepted........lol');
        /** @var \Dom\Loader $loader */
        $loader = $config->getDomLoader();
        
        $html = <<<HTML
<div>
  <h2>This is DOM Template example.</h2>
  <p>Hello Welcome to the new TK HTTP Framework... GOOD LUCK....</p>
</div>
HTML;
        
        // Template test
//        $template = $loader->doLoad($html);
//        $template->appendHtml($template->getDocument(false)->documentElement, '<p>------&gt; Some Dynamic Text</p>');
//        return $template;
        
        // string test
        return $html.'<p>This is a string test</p>';
        
    }
    
}
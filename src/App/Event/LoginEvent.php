<?php
namespace App\Event;

use Tk\EventDispatcher\Event;

/**
 * Class LoginEvent
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class LoginEvent extends Event
{
    /**
     * @var \Tk\Auth
     */
    private $auth = null;

    /**
     * Login data
     * 
     * @var array|object
     */
    private $data = null;


    /**
     * __construct
     * 
     * @param \Tk\Auth $auth
     * @param array $data  Login data
     */
    public function __construct($auth, $data = array())
    {
        $this->auth = $auth;
        $this->data = $data;
    }

    /**
     * @return \Tk\Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }
    
    
}
<?php
namespace Auth\Controller;

use Tk\Request;
use Dom\Template;
use App\Controller\Iface;

/**
 * Class Index
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Logout extends Iface
{

    /**
     *
     * @param Request $request
     * @return Template
     */
    public function doDefault(Request $request)
    {
        $event = new \Auth\Event\LoginEvent($this->getConfig()->getAuth());
        $this->getConfig()->getEventDispatcher()->dispatch('auth.onLogout', $event);
        
        
        
    }

    /**
     * Execute the renderer.
     *
     * @return mixed
     */
    public function show()
    {
        // TODO: Implement show() method.
    }
}
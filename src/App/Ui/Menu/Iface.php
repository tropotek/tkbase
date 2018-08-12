<?php
namespace App\Ui\Menu;

use Bs\Db\User;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
abstract class Iface extends \Dom\Renderer\Renderer implements \Dom\Renderer\DisplayInterface
{

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = $this->getTemplate();

        $template->insertText('username', $this->getUser()->username);
        $template->insertText('display-name', $this->getUser()->name);
        $template->insertText('siteTitle', substr($this->getConfig()->get('site.title'), 0, 40));

        if ($this->getConfig()->isDebug()) {
            $template->setChoice('debug');
        }

        if ($this->getUser()) {
            $perms = $this->getUser()->getRole()->getPermissions();
            foreach ($perms as $perm) {
                $template->setChoice($perm);
            }
        }

        return $template;
    }

    /**
     * @return static
     */
    static function create()
    {
        return new static();
    }

    /**
     * @return \App\Config
     */
    public function getConfig()
    {
        return \App\Config::getInstance();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->getConfig()->getUser();
    }


}
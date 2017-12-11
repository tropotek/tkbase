<?php
namespace App\Page;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class PublicPage extends Iface
{

    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     * @throws \Dom\Exception
     */
    public function __makeTemplate()
    {
        $tplFile =  $this->getConfig()->getSitePath() . $this->getConfig()->get('template.public');
        return \Dom\Loader::loadFile($tplFile);
    }

}
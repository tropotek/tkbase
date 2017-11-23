<?php
namespace App\Controller;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class AdminIface extends Iface implements \Dom\Renderer\DisplayInterface
{

    /**
     * @var \Tk\Ui\Admin\ActionPanel
     */
    protected $actionPanel = null;


    /**
     * Iface constructor.
     */
    public function __construct()
    {
        $this->actionPanel = \Tk\Ui\Admin\ActionPanel::create('Actions', 'fa fa-cogs');
        $this->actionPanel->addButton(\Tk\Ui\Button::create('Back', 'javascript: window.history.back();', 'fa fa-arrow-left'))
            ->addCss('btn-default btn-once back');
    }

    /**
     * @return \Tk\Ui\Admin\ActionPanel
     */
    public function getActionPanel()
    {
        return $this->actionPanel;
    }


}
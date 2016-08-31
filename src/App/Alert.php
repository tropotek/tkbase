<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 */
namespace App;

/**
 * A class to add and render Bootstrap alert boxes
 *
 * @see http://getbootstrap.com/components/#alerts
 */
class Alert extends \Dom\Renderer\Renderer implements \Dom\Renderer\DisplayInterface
{

    const SID = 'App_Alert';

    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'danger';

    /**
     * @var Alert
     */
    static $instance = null;

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * @var \Tk\Session
     */
    protected $session = null;


    /**
     * Singleton, Use getInstance()
     * Use:
     *   Alert::getInstance()
     *
     * @param array $msgArray
     * @param \Tk\Session $session
     */
    private function __construct($msgArray = array(), $session = null)
    {
        $this->messages = $msgArray;
        $this->session = $session;
    }

    /**
     * Get an instance of this object
     *
     * @param \Tk\Session $session
     * @return Alert
     */
    static function getInstance($session = null)
    {
        if (!$session)
            $session = \Tk\Config::getInstance()->getSession();

        if (!self::$instance && $session) {
            // TODO: Use the serialise and unserialise interface
            if (isset($session[self::SID])) {
                self::$instance = new self($session[self::SID], $session);
            } else {
                self::$instance = new self(array(), $session);
                $session[self::SID] = array();
            }
        }
        return self::$instance;
    }

    /**
     * add a message to display on next page load
     *
     * @param string $message
     * @param string $title
     * @param string $type Use the constants \Mod\Alert::TYPE_INFO, etc
     * @param string $icon
     */
    static function add($message, $title = 'Warning', $type = '', $icon = '')
    {
        $css = '';
        if ($type) {
            $css = 'alert-' . $type;
        }
        $title = htmlentities($title);
        $data = array('message' => $message, 'title' => $title, 'css' => $css);
        if ($icon) {
            $data['icon'] = $icon;
        }
        self::getInstance()->messages[$type][] = $data;

        self::getInstance()->session[self::SID] = self::getInstance()->messages;
    }


    static function addSuccess($message, $title = 'Success')
    {
        self::add($message, $title, self::TYPE_SUCCESS, 'icon-ok-sign');
    }

    static function addWarning($message, $title = 'Warning')
    {
        self::add($message, $title, self::TYPE_WARNING, 'icon-warning-sign');
    }

    static function addError($message, $title = 'Error')
    {
        self::add($message, $title, self::TYPE_ERROR, 'icon-remove-sign');
    }

    static function addInfo($message, $title = 'Information')
    {
        self::add($message, $title, self::TYPE_INFO, 'icon-exclamation-sign');
    }

    /**
     * Get message list
     *
     * @param string $type
     * @return array
     */
    public function getMessageList($type = '')
    {
        if (isset($this->messages[$type])) {
            return $this->messages[$type];
        }
        return $this->messages;
    }

    /**
     * show
     *
     * @return string
     */
    public function show()
    {
        if (self::hasMessages()) {
            $this->template = null; // Render with new template each time
            //if (!self::hasMessages()) return $this;
            $template = $this->getTemplate();
            foreach ($this->messages as $msgList) {
                foreach ($msgList as $data) {
                    $repeat = $template->getRepeat('row');
                    $repeat->insertText('title', $data['title']);
                    $repeat->insertHtml('message', $data['message']);
                    $repeat->addClass('row', $data['css']);
                    if (isset($data['icon'])) {
                        $repeat->addClass('icon', $data['icon']);
                        $repeat->setChoice('icon');
                    }
                    $repeat->appendRepeat();
                    $template->setChoice('show');
                }
            }
            $this->clear();
        }
        return $this;
    }

    /**
     * Check if there are any messages
     *
     * @return bool
     */
    static function hasMessages()
    {
        return count(self::getInstance()->messages);
    }

    /**
     * Clear the message list
     *
     * @return Alert
     */
    public function clear()
    {
        $this->messages = array();
        self::getInstance()->session[self::SID] = self::getInstance()->messages;
        return $this;
    }

    /**
     * makeTemplate
     *
     * @return string
     */
    public function __makeTemplate()
    {
        $xmlStr = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<div choice="show">
  <div class="alert" var="row" repeat="row">
    <button class="close noblock" data-dismiss="alert">&times;</button>
    <h4><i choice="icon" var="icon"></i> <strong var="title"></strong></h4>
    <span var="message"></span>
  </div>
</div>
XML;
        return \Dom\Loader::load($xmlStr);
    }

}

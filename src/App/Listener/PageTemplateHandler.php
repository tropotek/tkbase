<?php
namespace App\Listener;


use Bs\Controller\Iface;
use Bs\Db\User;
use Bs\Ui\AboutDialog;
use Bs\Ui\LogoutDialog;
use Bs\Uri;
use Exception;
use Tk\ConfigTrait;
use Tk\Event\Event;
use Tk\ObjectUtil;

/**
 * This object helps cleanup the structure of the controller code
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class PageTemplateHandler extends \Bs\Listener\PageTemplateHandler
{
    use ConfigTrait;

    /**
     * @param Event $event
     * @throws Exception
     */
    public function showPage(Event $event)
    {
        parent::showPage($event);

        $controller = Event::findControllerObject($event);
        if ($controller instanceof Iface) {
            $page = $controller->getPage();
            if (!$page) return;
            $template = $page->getTemplate();
            /** @var User $user */
            $user = $controller->getAuthUser();

            if ($user) {
                if (Uri::create()->getRoleType(ObjectUtil::getClassConstants($this->getConfig()->createRole(), 'TYPE')) != '') {
                    // About dialog\Uni\Uri::create()
                    $dialog = new AboutDialog();
                    $template->appendTemplate($template->getBodyElement(), $dialog->show());

                    // Logout dialog
                    $dialog = new LogoutDialog();
                    $template->appendTemplate($template->getBodyElement(), $dialog->show());
                }

                // Set permission choices
                $perms = $user->getRole()->getPermissions();
                foreach ($perms as $perm) {
                    $template->setVisible($perm);
                    $controller->getTemplate()->setVisible($perm);
                }

                //show user icon 'user-image'
                $img = $user->getImageUrl();
                if ($img) {
                    $template->setAttr('user-image', 'src', $img);
                }
            }

            $template->insertText('login-title', $this->getConfig()->get('site.title'));

            // Add anything to the page template here ...

        }
    }


}
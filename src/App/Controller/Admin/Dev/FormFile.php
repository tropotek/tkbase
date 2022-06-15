<?php
namespace App\Controller\Admin\Dev;

use App\Form\Field\FileUpload\FileUpload;
use Bs\Db\FileMap;
use Tk\Form\Event;
use Tk\Form\Field;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class FormFile extends \Bs\Controller\AdminIface
{

    /**
     * @var \Tk\Form
     */
    protected $form1 = null;

    /**
     * @var \Tk\Form
     */
    protected $form2 = null;


    /**
     */
    public function __construct()
    {
        $this->setPageTitle('Form Files');
        //$this->getActionPanel()->setEnabled(false);
        if ($this->getConfig()->getRequest()->has('del')) {
            $this->doDelete($this->getConfig()->getRequest());
        }
    }

    protected function createForm($id)
    {
        $form = $this->getConfig()->createForm($id);
        return $form;
    }

    /**
     * @param \Tk\Request $request
     */
    public function doDelete(\Tk\Request $request)
    {
        $fileId = $request->get('del');
        try {
            /** @var \Bs\Db\File $file */
            $file = \Bs\Db\FileMap::create()->find($fileId);
            if ($file) $file->delete();
        } catch (\Exception $e) {
            \Tk\ResponseJson::createJson(array('status' => 'err', 'msg' => $e->getMessage()), 500)->send();
            exit();
        }
        \Tk\ResponseJson::createJson(array('status' => 'ok'))->send();
        exit();
    }

    /**
     *
     * @param \Tk\Request $request
     * @throws \Exception
     */
    public function doDefault(\Tk\Request $request)
    {
        FileMap::create();
        $this->form1 = $this->createForm('form1');

        /** @var Field\File $fileField */
        $fileField = $this->form1->appendField(Field\File::create('files1[]', $this->getAuthUser()->getDataPath()))
            ->addCss('tk-fileinput')
            //->setAttr('multiple', 'multiple')
            //->setAttr('accept', '.png,.jpg,.jpeg,.gif')
            ->setNotes('Upload any related files. Multiple files can be selected.');

        /** @var Field\File $fileField */
        $fileField = $this->form1->appendField(Field\File::create('files2[]', $this->getAuthUser()->getDataPath()))
            ->addCss('tk-multiinput')
            ->setAttr('multiple', 'multiple')
            //->setAttr('accept', '.png,.jpg,.jpeg,.gif')
            ->setNotes('Upload any related files. Multiple files can be selected.');

        $files = FileMap::create()->findFiltered([
            'model' => $this->getAuthUser()
        ]);
        $v = json_encode($files->toArray());
        $fileField->setAttr('data-value', $v);
        $fileField->setAttr('data-prop-path', 'path');
        $fileField->setAttr('data-prop-id', 'id');

        $this->form1->appendField(new Event\Submit('update', array($this, 'doSubmit1')));
        $this->form1->appendField(new Event\Submit('save', array($this, 'doSubmit1')));
        $this->form1->appendField(new Event\Link('cancel', $this->getBackUrl()));
        $this->form1->execute();


        /***************************************************************************/



        $this->form2 = $this->createForm('form2');

        $this->form2->appendField(new FileUpload('fileupload', $this->getAuthUser()))
            //->addCss('tk-multiinput')
            //->setAttr('accept', '.png,.jpg,.jpeg,.gif')
            ->setNotes('Upload any related files. Multiple files can be selected.');

        $this->form2->appendField(new Event\Submit('update', array($this, 'doSubmit2')));
        $this->form2->appendField(new Event\Submit('save', array($this, 'doSubmit2')));
        $this->form2->appendField(new Event\Link('cancel', $this->getBackUrl()));
        $this->form2->execute();

    }

    /**
     * @param \Tk\Form $form
     * @param \Tk\Form\Event\Iface $event
     * @throws \Exception
     */
    public function doSubmit1($form, $event)
    {
        // Load the object with data from the form using a helper object
        //$this->getConfig()->getSubjectMapper()->mapForm($form->getValues(), $this->subject);
        //$form->addFieldErrors($this->subject->validate());

        if ($form->hasErrors()) {
            return;
        }

        /** @var \Tk\Form\Field\File $fileField */
        $fileField = $form->getField('files1');
        if ($fileField->hasFile()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            foreach ($fileField->getUploadedFiles() as $i => $file) {
                if (!\App\Config::getInstance()->validateFile($file->getClientOriginalName())) {
                    \Tk\Alert::addWarning('Illegal file type: ' . $file->getClientOriginalName());
                    continue;
                }
                try {
                    $filePath = $this->getConfig()->getDataPath() . $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName();
                    if (!is_dir(dirname($filePath))) {
                        mkdir(dirname($filePath), $this->getConfig()->getDirMask(), true);
                    }
                    $file->move(dirname($filePath), basename($filePath));
                    $oFile = \Bs\Db\FileMap::create()->findFiltered(array('model' => $this->getAuthUser(), 'path' => $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName()))->current();
                    if (!$oFile) {
                        $oFile = \Bs\Db\File::create($this->getAuthUser(), $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName(), $this->getConfig()->getDataPath() );
                    }
                    //$oFile->path = $this->report->getDataPath() . '/' . $file->getClientOriginalName();
                    $oFile->save();
                } catch (\Exception $e) {
                    \Tk\Log::error($e->__toString());
                    \Tk\Alert::addWarning('Error Uploading file: ' . $file->getClientOriginalName());
                }
            }
        }

        /** @var \Tk\Form\Field\File $fileField */
        $fileField = $form->getField('files2');
        if ($fileField->hasFile()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            foreach ($fileField->getUploadedFiles() as $i => $file) {
                if (!\App\Config::getInstance()->validateFile($file->getClientOriginalName())) {
                    \Tk\Alert::addWarning('Illegal file type: ' . $file->getClientOriginalName());
                    continue;
                }
                try {
                    $filePath = $this->getConfig()->getDataPath() . $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName();
                    if (!is_dir(dirname($filePath))) {
                        mkdir(dirname($filePath), $this->getConfig()->getDirMask(), true);
                    }
                    $file->move(dirname($filePath), basename($filePath));
                    $oFile = \Bs\Db\FileMap::create()->findFiltered(array('model' => $this->getAuthUser(), 'path' => $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName()))->current();
                    if (!$oFile) {
                        $oFile = \Bs\Db\File::create($this->getAuthUser(), $this->getAuthUser()->getDataPath() . '/' . $file->getClientOriginalName(), $this->getConfig()->getDataPath() );
                    }
                    //$oFile->path = $this->report->getDataPath() . '/' . $file->getClientOriginalName();
                    $oFile->save();
                } catch (\Exception $e) {
                    \Tk\Log::error($e->__toString());
                    \Tk\Alert::addWarning('Error Uploading file: ' . $file->getClientOriginalName());
                }
            }
        }

        \Tk\Alert::addSuccess('Record saved!');
        $event->setRedirect($this->getConfig()->getBackUrl());
        if ($form->getTriggeredEvent()->getName() == 'save') {
            $event->setRedirect(\Tk\Uri::create());
        }
    }

    /**
     * @param \Tk\Form $form
     * @param \Tk\Form\Event\Iface $event
     * @throws \Exception
     */
    public function doSubmit2($form, $event)
    {

        // Load the object with data from the form using a helper object
        //$this->getConfig()->getSubjectMapper()->mapForm($form->getValues(), $this->subject);
        //$form->addFieldErrors($this->subject->validate());


        if ($form->hasErrors()) {
            return;
        }



        \Tk\Alert::addSuccess('Record saved!');
        $event->setRedirect($this->getConfig()->getBackUrl());
        if ($form->getTriggeredEvent()->getName() == 'save') {
            $event->setRedirect(\Tk\Uri::create());
        }
    }

    /**
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();

        $template->appendTemplate('form1', $this->form1->getRenderer()->show());
        $template->appendTemplate('form2', $this->form2->getRenderer()->show());

        return $template;
    }

    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div class="">

  <div class="tk-panel" data-panel-title="Default File Field #1" data-panel-icon="fa fa-form" var="form1"></div>
  <div class="tk-panel" data-panel-title="jQuery-File-Upload" data-panel-icon="fa fa-form" var="form2"></div>
  <div class="tk-panel" data-panel-title="SQL Required" data-panel-icon="fa fa-form" var="sql">
<pre>  
CREATE TABLE IF NOT EXISTS file
(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) UNSIGNED NOT NULL DEFAULT 0,
    fkey VARCHAR(64) DEFAULT '' NOT NULL,
    fid INT DEFAULT 0 NOT NULL,
    label VARCHAR(128) default '',
    path TEXT NULL,
    bytes INT DEFAULT 0 NOT NULL,
    mime VARCHAR(255) DEFAULT '' NOT NULL,
    active TINYINT(1) default 0,
    notes TEXT NULL,
    hash VARCHAR(128) DEFAULT '' NOT NULL,
    modified datetime NOT NULL,
    created datetime NOT NULL,
    KEY user_id (user_id),
    KEY fkey (fkey),
    KEY fkey_2 (fkey, fid)
);
</pre>
  </div>
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }


}
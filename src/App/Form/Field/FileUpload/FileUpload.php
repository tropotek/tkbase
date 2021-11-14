<?php
namespace App\Form\Field\FileUpload;

use Bs\Db\File;
use Bs\Db\FileIface;
use Bs\Db\FileMap;
use Bs\Db\Traits\ForeignModelTrait;
use Tk\Db\Map\Model;
use Tk\Db\ModelInterface;
use Tk\Db\Tool;
use Tk\Request;
use Tk\Uri;

/**
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @see http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class FileUpload extends \Tk\Form\Field\Iface
{
    use ForeignModelTrait;

    /**
     * This is the relative data path to the site root path
     * This will allow us to create file paths and url paths
     * @var string
     */
    protected $dataPath = '';

    /**
     * @var File[]|array
     */
    protected $fileList = null;

    /**
     * @var int
     */
    protected $maxFiles = 0;

    /**
     * @var string
     */
    private $orderBy = 'label';

    /**
     * @var null|TkUploadHandler
     */
    private $_upload = null;



    /**
     * @param string $name
     * @param Model|ModelInterface|FileIface $model
     * @param string $fileDbClass
     * @param string $orderBy
     * @throws \Exception
     */
    public function __construct($name, $model, $fileDbClass = '/Bs/Db/File')
    {
        $this->setModel($model);
        parent::__construct($name);
        $this->setArrayField(true);
        $this->setAttr('multiple');

        // Find the upload relative data path
        $this->dataPath = '/uploads';
        if (method_exists($model, 'getDataPath'))
            $this->dataPath = $model->getDataPath();
        if (!is_dir($this->getDataPath())) {
            mkdir($this->getDataPath(), 0777, true);
        }

    }

    /**
     * Get the field value(s).
     *
     * @return string|array
     */
    public function getValue()
    {
        if (is_string(parent::getValue()))
            $this->setValue(trim(parent::getValue()));
        return parent::getValue();
    }

    /**
     * @param array|\ArrayObject $values
     * @return FileUpload
     */
    public function load($values)
    {
        $r = parent::load($values);

        $list = $this->getFileList();
        //vd($values, $list->count());

//        // When the value does not exist it is ignored (may not be the desired result for unselected checkbox or empty select box)
//        if (array_key_exists($this->getName(), $values)) {
//            $this->setValue($values[$this->getName()]);
//        }
        return $r;
    }

    /**
     * This is called only once the form has been submitted
     *   and new data loaded into the fields
     *
     */
    public function execute()
    {

//        $v = $this->handleUpload($this->getName(), [
//            //"allowed_exts" => array("jpg", "png"),
//            //"filename" => __DIR__ . "/images/" . $id . ".{ext}",
//            "result_callback" => [$this, "onResult"],
//            "filename_callback" => [$this, "onFilename"]
//        ]);

    }



    function onFilename($name, $ext, $fileinfo)
    {
        $this->_upload = new TkUploadHandler();
        vd(
            $_REQUEST,
            $this->getAttrList(),
            $name,
            $ext,
            $fileinfo
        );
        $path = $this->getDataPath() . '/' . $name . '.' . $ext;
        vd($path);
        return $path;
    }

	function onResult(&$result, $filename, $name, $ext, $fileinfo)
	{
        $this->_upload = new TkUploadHandler();
	    vd(
            $_REQUEST,
	        $result,
            $filename,
            $name,
            $ext,
            $fileinfo
        );
	}

    /**
     * Get the element HTML
     *
     * @return string|\Dom\Template
     */
    public function show()
    {

        $template = $this->getTemplate();
        if (!$template->keyExists('var', 'element')) {
            return $template;
        }
//        $baseurl = $this->getConfig()->getSiteUrl().'/src/App/Form/Field/FileUpload/fancy-file-uploader';
//        // Include CSS
//        $template->appendCssUrl(Uri::create($baseurl . '/fancy_fileupload.css'));
//        // Include Js
//        $template->appendJsUrl(Uri::create($baseurl . '/jquery.fileupload.js'));
//        $template->appendJsUrl(Uri::create($baseurl . '/jquery.iframe-transport.js'));
//        $template->appendJsUrl(Uri::create($baseurl . '/jquery.fancy-fileupload.js'));

        $template->setAttr('element', 'data-action', $this->makeInstanceKey($this->getName()));

        // The main application script
        $js = <<<JS
jQuery(function ($) {
  'use strict';
  function init() {
    var form = $(this);
    
    
    
   }
   $('form').on('init', document, init).each(init);
});
JS;
        $template->appendJs($js);

        // Set the input type attribute

        // Set the field value
        if ($template->getVar('element')->nodeName == 'input' ) {
            $value = $this->getValue();
            if ($value !== null && !is_array($value)) {
                $template->setAttr('element', 'value', $value);
            }
        }

        $this->decorateElement($template);
        return $template;
    }

    /**
     * makeTemplate
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div class="tk-file-upload-control">
  <input type="file" class="form-control form-control-lg" var="element"/>
</div>
HTML;
        return \Dom\Loader::load($xhtml);
    }


    /**
     * @return \Bs\Db\File[]|\Tk\Db\Map\ArrayObject|null
     */
    public function getFileList()
    {
        if (!$this->fileList) {
            $model = $this->getModel();
//            $interfaces = class_implements($model);
//            vd($interfaces);
//            if (isset($interfaces['FileIface'])) {
            if ($model instanceof FileIface) {
                $this->fileList = $model->getFileList(Tool::create($this->orderBy, $this->maxFiles));
            } else {
                $this->fileList = FileMap::create()->findFiltered([
                    'model' => $this->getModel()
                ], Tool::create($this->orderBy, $this->maxFiles));
            }
        }
        return $this->fileList;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return FileUpload
     */
    public function setOrderBy(string $orderBy): FileUpload
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFiles(): int
    {
        return $this->maxFiles;
    }

    /**
     * @param int $maxFiles
     * @return FileUpload
     */
    public function setMaxFiles(int $maxFiles): FileUpload
    {
        $this->maxFiles = $maxFiles;
        return $this;
    }

    /**
     * Return the full directory data path
     *
     * @return string
     */
    public function getDataPath()
    {
        return $this->getConfig()->getDataPath() . $this->dataPath;
    }

    /**
     * Return the full directory data URL
     *
     * @return string
     */
    public function getDataUrl()
    {
        return $this->getConfig()->getDataUrl() . $this->dataPath;
    }

}
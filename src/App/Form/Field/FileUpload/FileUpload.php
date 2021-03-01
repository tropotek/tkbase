<?php
namespace App\Form\Field\FileUpload;

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
     * @var \Bs\Db\File[]|\Tk\Db\Map\ArrayObject|null
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
     * @param string $name
     * @param Model|ModelInterface $model
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
        vd($values, $list->count());

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
        if ($this->getForm()->isSubmitted()) {
        }

        $v = $this->handleUpload($this->getName(), [
            //"allowed_exts" => array("jpg", "png"),
            //"filename" => __DIR__ . "/images/" . $id . ".{ext}",
            "result_callback" => [$this, "onResult"],
            "filename_callback" => [$this, "onFilename"]
        ]);
        if ($v) {
            vd($v);
        }

    }



    function onFilename($name, $ext, $fileinfo)
    {
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
        $baseurl = $this->getConfig()->getSiteUrl().'/src/App/Form/Field/FileUpload/fancy-file-uploader';
        // Include CSS
        $template->appendCssUrl(Uri::create($baseurl . '/fancy_fileupload.css'));
        // Include Js
        $template->appendJsUrl(Uri::create($baseurl . '/jquery.fileupload.js'));
        $template->appendJsUrl(Uri::create($baseurl . '/jquery.iframe-transport.js'));
        $template->appendJsUrl(Uri::create($baseurl . '/jquery.fancy-fileupload.js'));
        $template->setAttr('element', 'data-action', $this->makeInstanceKey($this->getName()));

        // The main application script
        $js = <<<JS
jQuery(function ($) {
  'use strict';
  function init() {
    var form = $(this);
    form.find('.tk-file-upload-control input').each(function (){
      var file = $(this);
      file.FancyFileUpload({
        //url: document.href, 
        edit: true,
	    params : {
		  action : file.data('action'),
		  getFilesFromResponse: function (data) {
		    console.log(arguments);
		  }
	    },
	    maxfilesize : 1000000,
	    
	    // postinit: function () {
      //     console.log(arguments);
      //     console.log(this);
      //     var b = fetch(config.siteUrl+'/html/app/img/favicon.png')
      //     .then(function(response) {
      //       return response.blob();
      //     });
      //    
      //     $(this).fileupload('add', { files: [ b ] });
      //    
	    // },
	    
		added : function(e, data) { // Auto upload
			// It is okay to simulate clicking the start upload button.
			this.find('.ff_fileupload_actions button.ff_fileupload_start_upload').click();
		}
	  });
    });
    
//     form.find('input[type=file]').each(function() {
//       var fileinput = $(this);
//    
//       // Do something with the file input.
//       //fileinput.attr('accept', '.png; image/png');
//       var b = fetch(config.siteUrl+'/html/app/img/favicon.png')
//       .then(function(response) {
//         return response.blob();
//       });
//      
//       fileinput.fileupload('add', { files: [ b ] });
// //    
// //      fileupload.data('fancy-fileupload').settings.accept = ['png'];
// //    
// //      // Can even alter the underlying jQuery File Uploader (e.g. inject a canvas PNG blob).
// //      fileinput.fileupload('add', { files: [ new Blob(...) ] });
//     });
    
    
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
            $this->fileList = FileMap::create()->findFiltered([
                'model' => $this->getModel()
            ], Tool::create($this->orderBy, $this->maxFiles));
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
    
    /***************************** Fancy File Upload Methods *******************************/
    /** https://github.com/cubiclesoft/jquery-fancyfileuploader */

    /**
     * @param $filekey
     * @param array $options
     * @return array|bool[]|false|mixed
     */
    public function handleUpload($filekey, $options = array())
    {
        //vd($_REQUEST, $_FILES, $filekey, $this->makeInstanceKey($filekey));
        //if (!isset($_REQUEST["fileuploader"]) && !isset($_POST["fileuploader"])) return false;
        if ((!isset($_REQUEST['action']) && !isset($_POST['action'])) || $_REQUEST['action'] != $this->makeInstanceKey($filekey)) return false;
        //header("Content-Type: application/json");
        if (isset($options["allowed_exts"])) {
            $allowedexts = array();
            if (is_string($options["allowed_exts"])) $options["allowed_exts"] = explode(",", $options["allowed_exts"]);
            foreach ($options["allowed_exts"] as $ext) {
                $ext = strtolower(trim(trim($ext), "."));
                if ($ext !== "") $allowedexts[$ext] = true;
            }
        }

        $files = $this->normalizeFiles($filekey);
//vd($files);
        if (!isset($files[0])) $result = array("success" => false, "error" => $this->ffTranslate("File data was submitted but is missing."), "errorcode" => "bad_input");
        else if (!$files[0]["success"]) $result = $files[0];
        else if (isset($options["allowed_exts"]) && !isset($allowedexts[strtolower($files[0]["ext"])])) {
            $result = array(
                "success" => false,
                "error" => $this->ffTranslate("Invalid file extension.  Must be one of %s.", "'." . implode("', '.", array_keys($allowedexts)) . "'"),
                "errorcode" => "invalid_file_ext"
            );
        } else {
            // For chunked file uploads, get the current filename and starting position from the incoming headers.
            $name = $this->getChunkFilename();
            if ($name !== false) {
                $startpos = $this->getFileStartPosition();

                $name = substr($name, 0, -(strlen($files[0]["ext"]) + 1));
                if (isset($options["filename_callback"]) && is_callable($options["filename_callback"])) $filename = call_user_func_array($options["filename_callback"], array($name, strtolower($files[0]["ext"]), $files[0]));
                else if (isset($options["filename"])) $filename = str_replace(array("{name}", "{ext}"), array($name, strtolower($files[0]["ext"])), $options["filename"]);
                else  $filename = false;

                if (!is_string($filename)) $result = array("success" => false, "error" => $this->ffTranslate("The server did not set a valid filename."), "errorcode" => "invalid_filename");
                else if (isset($options["limit"]) && $options["limit"] > -1 && $startpos + filesize($files[0]["file"]) > $options["limit"]) $result = array("success" => false, "error" => $this->ffTranslate("The server file size limit was exceeded."), "errorcode" => "file_too_large");
                else {
                    if (file_exists($filename) && $startpos === filesize($filename)) $fp = @fopen($filename, "ab");
                    else {
                        $fp = @fopen($filename, ($startpos > 0 && file_exists($filename) ? "r+b" : "wb"));
                        if ($fp !== false) @fseek($fp, $startpos, SEEK_SET);
                    }

                    $fp2 = @fopen($files[0]["file"], "rb");
                    if ($fp === false) $result = array("success" => false, "error" => $this->ffTranslate("Unable to open a required file for writing."), "errorcode" => "open_failed", "info" => $filename);
                    else if ($fp2 === false) $result = array("success" => false, "error" => $this->ffTranslate("Unable to open a required file for reading."), "errorcode" => "open_failed", "info" => $files[0]["file"]);
                    else {
                        do {
                            $data2 = @fread($fp2, 1048576);
                            if ($data2 == "") break;

                            @fwrite($fp, $data2);
                        } while (1);

                        fclose($fp2);
                        fclose($fp);

                        $result = array(
                            "success" => true
                        );
                    }
                }
            } else {
                $filename = $files[0]["name"];
                $name = substr($files[0]["name"], 0, -(strlen($files[0]["ext"]) + 1));
vd($name);
                if (isset($options["filename_callback"]) && is_callable($options["filename_callback"])) $filename = call_user_func_array($options["filename_callback"], array($name, strtolower($files[0]["ext"]), $files[0]));
                else if (isset($options["filename"])) $filename = str_replace(array("{name}", "{ext}"), array($name, strtolower($files[0]["ext"])), $options["filename"]);
                else $filename = false;
                //else $filename = $files[0]["name"];
vd($filename);
                if (!is_string($filename)) $result = array("success" => false, "error" => $this->ffTranslate("The server did not set a valid filename."), "errorcode" => "invalid_filename");
                else if (isset($options["limit"]) && $options["limit"] > -1 && filesize($files[0]["file"]) > $options["limit"]) $result = array("success" => false, "error" => $this->ffTranslate("The server file size limit was exceeded."), "errorcode" => "file_too_large");
                else {
                    @copy($files[0]["file"], $filename);
                    $result = array(
                        "success" => true
                    );
                }
            }
        }

        if ($result["success"] && isset($options["result_callback"]) && is_callable($options["result_callback"])) call_user_func_array($options["result_callback"], array(&$result, $filename, $name, strtolower($files[0]["ext"]), $files[0], (isset($options["result_callback_opts"]) ? $options["result_callback_opts"] : false)));

        if (isset($options["return_result"]) && $options["return_result"]) return $result;
//vd(json_encode($result, JSON_UNESCAPED_SLASHES));
        \Tk\ResponseJson::createJson(json_encode($result, JSON_UNESCAPED_SLASHES))->send();
        //echo json_encode($result, JSON_UNESCAPED_SLASHES);
        exit();
    }


    /**
     * Copy included for class self-containment.
     * Makes an input filename safe for use.
     * Allows a very limited number of characters through.
     *
     * @param $filename
     * @return string|string[]|null
     */
    public function filenameSafe($filename)
    {
        return preg_replace('/\s+/', "-", trim(trim(preg_replace('/[^A-Za-z0-9_.\-]/', " ", $filename), ".")));
    }

    /**
     * @param $key
     * @return array
     */
    public function normalizeFiles($key)
    {
        $result = array();
        if (isset($_FILES) && is_array($_FILES) && isset($_FILES[$key]) && is_array($_FILES[$key])) {
            $currfiles = $_FILES[$key];
            if (isset($currfiles["name"]) && isset($currfiles["type"]) && isset($currfiles["tmp_name"]) && isset($currfiles["error"]) && isset($currfiles["size"])) {
                if (is_string($currfiles["name"])) {
                    $currfiles["name"] = array($currfiles["name"]);
                    $currfiles["type"] = array($currfiles["type"]);
                    $currfiles["tmp_name"] = array($currfiles["tmp_name"]);
                    $currfiles["error"] = array($currfiles["error"]);
                    $currfiles["size"] = array($currfiles["size"]);
                }
                $y = count($currfiles["name"]);
                for ($x = 0; $x < $y; $x++) {
                    if ($currfiles["error"][$x] != 0) {
                        switch ($currfiles["error"][$x]) {
                            case 1:
                                $msg = "The uploaded file exceeds the 'upload_max_filesize' directive in 'php.ini'.";
                                $code = "upload_err_ini_size";
                                break;
                            case 2:
                                $msg = "The uploaded file exceeds the 'MAX_FILE_SIZE' directive that was specified in the submitted form.";
                                $code = "upload_err_form_size";
                                break;
                            case 3:
                                $msg = "The uploaded file was only partially uploaded.";
                                $code = "upload_err_partial";
                                break;
                            case 4:
                                $msg = "No file was uploaded.";
                                $code = "upload_err_no_file";
                                break;
                            case 6:
                                $msg = "The configured temporary folder on the server is missing.";
                                $code = "upload_err_no_tmp_dir";
                                break;
                            case 7:
                                $msg = "Unable to write the temporary file to disk.  The server is out of disk space, incorrectly configured, or experiencing hardware issues.";
                                $code = "upload_err_cant_write";
                                break;
                            case 8:
                                $msg = "A PHP extension stopped the upload.";
                                $code = "upload_err_extension";
                                break;
                            default:
                                $msg = "An unknown error occurred.";
                                $code = "upload_err_unknown";
                                break;
                        }
                        $entry = array(
                            "success" => false,
                            "error" => $this->ffTranslate($msg),
                            "errorcode" => $code
                        );
                    } else if (!is_uploaded_file($currfiles["tmp_name"][$x])) {
                        $entry = array(
                            "success" => false,
                            "error" => $this->ffTranslate("The specified input filename was not uploaded to this server."),
                            "errorcode" => "invalid_input_filename"
                        );
                    } else {
                        $currfiles["name"][$x] = $this->filenameSafe($currfiles["name"][$x]);
                        $pos = strrpos($currfiles["name"][$x], ".");
                        $fileext = ($pos !== false ? (string)substr($currfiles["name"][$x], $pos + 1) : "");
                        $entry = array(
                            "success" => true,
                            "file" => $currfiles["tmp_name"][$x],
                            "name" => $currfiles["name"][$x],
                            "ext" => $fileext,
                            "type" => $currfiles["type"][$x],
                            "size" => $currfiles["size"][$x]
                        );
                    }
                    $result[] = $entry;
                }
            }
        }
        return $result;
    }

    /**
     * @return false|float|int|mixed
     */
    public function getMaxUploadFileSize()
    {
        $maxpostsize = floor($this->convertUserStrToBytes(ini_get("post_max_size")) * 3 / 4);
        if ($maxpostsize > 4096) $maxpostsize -= 4096;
        $maxuploadsize = $this->convertUserStrToBytes(ini_get("upload_max_filesize"));
        if ($maxuploadsize < 1) $maxuploadsize = ($maxpostsize < 1 ? -1 : $maxpostsize);
        return ($maxpostsize < 1 ? $maxuploadsize : min($maxpostsize, $maxuploadsize));
    }

    // Copy included for FlexForms self-containment.
    public function convertUserStrToBytes($str)
    {
        $str = trim($str);
        $num = (double)$str;
        if (strtoupper(substr($str, -1)) == "B") $str = substr($str, 0, -1);
        switch (strtoupper(substr($str, -1))) {
            case "P":
                $num *= 1024;
            case "T":
                $num *= 1024;
            case "G":
                $num *= 1024;
            case "M":
                $num *= 1024;
            case "K":
                $num *= 1024;
        }
        return $num;
    }

    /**
     * @return false|string|string[]|null
     */
    public function getChunkFilename()
    {
        if (isset($_SERVER["HTTP_CONTENT_DISPOSITION"])) {
            // Content-Disposition: attachment; filename="urlencodedstr"
            $str = $_SERVER["HTTP_CONTENT_DISPOSITION"];
            if (strtolower(substr($str, 0, 11)) === "attachment;") {
                $pos = strpos($str, "\"", 11);
                $pos2 = strrpos($str, "\"");
                if ($pos !== false && $pos2 !== false && $pos < $pos2) {
                    $str = $this->filenameSafe(rawurldecode(substr($str, $pos + 1, $pos2 - $pos - 1)));
                    if ($str !== "") return $str;
                }
            }
        }

        return false;
    }

    /**
     * @return float|int
     */
    public function getFileStartPosition()
    {
        if (isset($_SERVER["HTTP_CONTENT_RANGE"]) || isset($_SERVER["HTTP_RANGE"])) {
            // Content-Range: bytes (*|integer-integer)/(*|integer-integer)
            $vals = explode(" ", preg_replace('/\s+/', " ", str_replace(",", "", (isset($_SERVER["HTTP_CONTENT_RANGE"]) ? $_SERVER["HTTP_CONTENT_RANGE"] : $_SERVER["HTTP_RANGE"]))));
            if (count($vals) === 2 && strtolower($vals[0]) === "bytes") {
                $vals = explode("/", trim($vals[1]));
                if (count($vals) === 2) {
                    $vals = explode("-", trim($vals[0]));
                    if (count($vals) === 2) return (double)$vals[0];
                }
            }
        }
        return 0;
    }

    /**
     * @return false|mixed|string
     */
    public function ffTranslate()
    {
        $args = func_get_args();
        if (!count($args)) return "";
        return call_user_func_array((defined("CS_TRANSLATE_FUNC") && function_exists(CS_TRANSLATE_FUNC) ? CS_TRANSLATE_FUNC : "sprintf"), $args);
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
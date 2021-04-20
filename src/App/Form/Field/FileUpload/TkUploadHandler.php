<?php
namespace App\Form\Field\FileUpload;

/**
 * Class TkUploadHandler
 *
 * @package App\Form\Field\FileUpload
 */
class TkUploadHandler extends UploadHandler
{

    /**
     * TkUploadHandler constructor.
     *
     * @param null|array $options
     * @param bool $initialize
     * @param null|array $error_messages
     */
    public function __construct($options = null, $initialize = true, $error_messages = null)
    {
        parent::__construct($this->options, $initialize, $error_messages);

    }



}

<?php

namespace XmlUploader;

use XmlUploader\Constants\ErrorMessages;
use XmlUploader\Constants\Files;
use XmlUploader\Errors\ErrorOptionFile;
use XmlUploader\Errors\ErrorQuantity;

final class XmlUploader
{
    private $xml;
    private $files;

    public function __construct(Options $options)
    {
        $this->options = $options;
        $this->validateXmls();
    }

    /**
     * @throws ErrorOptionFile
     *
     * @return array|XmlHandler
     */
    private function validateXmls()
    {
        $this->verifyParamName();
        $this->files = $_FILES[$this->options->paramName];
        $this->hasRequiredQuantityFiles($files);

        if (is_array($this->files[Files::TEMP_NAME])) {
            $this->xml = $this->filesLoop($files);
        } else {
            $this->xml = new XmlHandler($this->files[Files::TEMP_NAME], $this->files[Files::NAME], $this->files[Files::SIZE], $this->options);
        }

        return $this->xml;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param $files
     *
     * @throws ErrorQuantity
     */
    private function hasRequiredQuantityFiles($files)
    {
        $quantity = count($files);
        if ($this->options->quantity >= $quantity) {
            throw new ErrorQuantity(ErrorMessages::ERROR_QUANTITY);
        }
    }

    /**
     * @param $files
     *
     * @return array
     */
    private function filesLoop($files):array
    {
        $handler = [];
        foreach ($files[Files::TEMP_NAME] as $key => $file) {
            $name = $files[Files::NAME][$key];
            $size = $files[Files::SIZE][$key];
            $handler[] = new XmlHandler($file, $name, $size, $this->options);
        }

        return $handler;
    }

    /**
     * @throws ErrorOptionFile
     */
    private function verifyParamName()
    {
        if (isset($_FILES[$this->options->paramName]) == false) {
            throw new ErrorOptionFile(ErrorMessages::ERROR_OPTION_FILENAME);
        }
    }
}

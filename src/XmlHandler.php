<?php

namespace XmlUploader;

use DOMDocument;
use DOMException;
use XmlUploader\Constants\ErrorMessages;
use XmlUploader\Constants\Files;
use XmlUploader\Errors\ErrorExtension;
use XmlUploader\Errors\ErrorMissingRequiredNodes;
use XmlUploader\Errors\ErrorParse;
use XmlUploader\Errors\ErrorSize;
use XmlUploaderErrorExtension;

final class XmlHandler
{
    public $xml;

    function __construct($file, $name, $size, $option)
    {
        $this->isValidXml($file, $name, $size, $option);
    }

    /**
     * @param $file
     * @param string $name
     * @param Options $option
     */
    public function isValidXml($file, $name = '', $size, Options $option)
    {

        $requiredNodes = $option->nodes;
        $this->hasXmlExtension($name);
        $this->hasRequiredSize($size, $option);
        $dom = $this->parseFile($file);

        if (empty($option->nodes) == false) {
            $this->hasRequiredNodes($dom, $requiredNodes);
        }

        $this->xml = $dom;
    }

    /**
     * @param $name
     * @throws ErrorExtension
     */
    private function hasXmlExtension($name)
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        if ($ext != Files::XML) {
            throw new ErrorExtension(ErrorMessages::ERROR_EXTENSION);
        }
    }

    /**
     * @param $file
     * @return DOMDocument
     * @throws ErrorParse
     */
    private function parseFile($file)
    {
        try {
            set_error_handler(function () {});
            $dom = new DOMDocument();
            $dom->loadXml($this->file_get_contents_utf8($file));
            restore_error_handler();
        } catch (DOMException $exception) {
            throw new ErrorParse(ErrorMessages::ERROR_PARSE);
        }
        return $dom;
    }

    /**
     * @param $node
     * @param $requiredNodes
     */
    private function searchNodeRequire($node, &$requiredNodes)
    {
        if (in_array($node->nodeName, $requiredNodes)) {
            $key = array_search($node->nodeName, $requiredNodes);
            unset($requiredNodes[$key]);
        }
    }


    /**
     * @param $dom
     * @param $requiredNodes
     * @throws ErrorMissingRequiredNodes
     */
    private function hasRequiredNodes($dom, &$requiredNodes)
    {
        foreach ($dom->documentElement->childNodes as $node) {
            $this->searchNodeRequire($node, $requiredNodes);
        }
        if (empty($requiredNodes) == false) {
            throw new ErrorMissingRequiredNodes(ErrorMessages::ERROR_MISSING_REQUIRED_NODES);
        }
    }

    /**
     * @param $fn
     * @return string
     */
    private function file_get_contents_utf8($fn) {
        $content = file_get_contents($fn);
        return mb_convert_encoding($content, Files::UTF8, mb_detect_encoding($content, Files::ENCODING_LIST, true));
    }

    /**
     * @param $size
     * @param Options $option
     * @throws ErrorSize
     */
    public function hasRequiredSize($size, Options $option)
    {
        if ($size > $option->getSize()) {
            throw  new ErrorSize(ErrorMessages::ERROR_SIZE);
        }
    }
}
<?php

namespace XmlUploader;

use XmlUploader\Constants\SizeValues;

final class Options
{
    public $paramName = 'file';
    public $quantity = 1;
    public $tagName = '';
    public $nodes = [];
    private $size = '50' * SizeValues::KB;

    /**
     * @param $size
     * @param $type
     */
    public function setSize(int $size, int $type) {

        $this->size = $size *  $type;

    }

    /**
     * @return string
     */
    public function getSize() {
        return $this->size;
    }
}
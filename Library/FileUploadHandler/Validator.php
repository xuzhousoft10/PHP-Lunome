<?php
namespace X\Library\FileUploadHandler;
/**
 * 
 */
class Validator {
    private $file = null;
    public function __construct() {}
    public function setTypes(){}
    public function setMaxSize() {}
    public function setMinSize() {}
    public function validate() {}
    public function getErrors() {}
    public function hasError(){}
}
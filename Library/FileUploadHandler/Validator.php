<?php
namespace X\Library\FileUploadHandler;
/**
 * 
 */
class Validator {
    /**
     * @var File
     */
    private $file = null;
    
    /**
     * @var array
     */
    private $errors = array();
    
    /**
     * @param File $file
     */
    public function __construct( File $file ) {
        $this->file = $file;
    }
    
    /**
     * @var array
     */
    private $types = array();
    
    /**
     * @param array $types
     * @return \X\Library\FileUploadHandler\Validator
     */
    public function setTypes( $types ){
        $this->types = $types;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function validateType() {
        return in_array($this->file->getType(), $this->types);
    }
    
    /**
     * @var integer
     */
    private $maxSize = null;
    
    /**
     * @param integer $maxSize
     * @return \X\Library\FileUploadHandler\Validator
     */
    public function setMaxSize( $maxSize ) {
        $this->maxSize = $maxSize;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function validateMaxSize() {
        return $this->file->getSize() <= $this->maxSize;
    }
    
    /**
     * @var integer
     */
    private $minSize = null;
    
    /**
     * @param integer $minSize
     * @return \X\Library\FileUploadHandler\Validator
     */
    public function setMinSize( $minSize ) {
        $this->minSize = $minSize;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function validateMinSize() {
        return $this->file->getSize() > $this->minSize;
    }
}
<?php
namespace X\Library\FileUploadHandler;
/**
 * 
 */
class Handler {
    /**
     * @param string $name
     * @return \X\Library\FileUploadHandler\Handler
     */
    public static function setup($name) {
        return new Handler($name);
    }
    
    /**
     * @var X\Library\FileUploadHandler\File[]
     */
    private $files = array();
    
    /**
     * @param string $name
     */
    private function __construct( $name ) {
        if ( is_array($_FILES[$name]['name']) ) {
            foreach($_FILES[$name] as $key1 => $value1) {
                foreach($value1 as $key2 => $value2) {
                    $this->files[$key2][$key1] = $value2;
                }
            }
        } else {
            $this->files[] = $_FILES[$name];
        }
        foreach ( $this->files as $index => $fileInfo ) {
            $this->files[$index] = new File($fileInfo);
        }
    }
    
    /**
     * @return boolean
     */
    public function hasFile() {
        return !empty($this->files);
    }
    
    /**
     * @return number
     */
    public function count() {
        return count($this->files);
    }
    
    /**
     * @param number $index
     * @return \X\Library\FileUploadHandler\File
     */
    public function getFile( $index=0 ){
        return $this->files[$index];
    }
}
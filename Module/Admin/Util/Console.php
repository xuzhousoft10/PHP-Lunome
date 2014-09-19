<?php
namespace X\Module\Admin\Util;
class Console extends \X\Core\Basic {
    protected $inputHandler = null;
    
    public function __construct() {
        $debug = false;
        
        if ( $debug ) {
            $this->inputHandler = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'console.txt','r');
        } else {
            $this->inputHandler = fopen('php://stdin','r');
        }
    }
    
    public function printString( $string ) {
        echo $string;
    }
    
    public function printLine( $line ) {
        echo call_user_func_array('sprintf', func_get_args())."\n";
    }
    
    public function getLine() {
        $line = fgets($this->inputHandler);
        $line = str_replace("\r\n", '', $line);
        $line = str_replace("\n", '', $line);
        $line = str_replace("\r", '', $line);
        return $line;
    }
}
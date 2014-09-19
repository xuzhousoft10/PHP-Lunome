<?php
/**
 * 
 */
namespace X\Library\XData;

/**
 * The XString class use to handle most string operations.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XString extends \stdClass {
    /**
     * Default encoding.
     *
     * @var string
     */
    const DEFAULT_ENCODING = 'UTF-8';
    
    /**
     *
     * @var unknown
     */
    protected $encoding = self::DEFAULT_ENCODING;
    
    /**
     * 
     * @return \X\Library\XData\XString
     */
    public function setEncoding( $encoding ) {
        $this->encoding = $encoding;
        return $this;
    }
    
    /**
     * 
     * @return \X\Library\XData\unknown
     */
    public function getEncoding() {
        return $this->encoding;
    }
    
    /**
     *
     * @var unknown
     */
    protected $string = null;
    
    /**
     *
     * @param string $string
     */
    public function __construct( $string = '' ) {
        $this->string = $string;
    }
    
    /**
     *
     * @return \X\Library\XUtil\unknown
     */
    public function __toString() {
        return $this->string;
    }
    
    public function toString() {
        return $this->string;
    }
    
    /**
     * 
     * @return \X\Library\XData\XString
     */
    public static function str( $string='' ) {
        return new XString($string);
    }
    
    /**
     * 
     * @return \X\Library\XData\XString
     */
    public function trim() {
        $this->string = trim($this->string);
        return $this;
    }
    
    /**
     * 
     * @return \X\Library\XData\XString
     */
    public function toLower() {
        $this->string = mb_convert_case($this->string, MB_CASE_LOWER, $this->encoding);
        return $this;
    }
    
    /**
     * 
     * @return \X\Library\XData\XString
     */
    public function toUpper() {
        $this->string = mb_convert_case($this->string, MB_CASE_UPPER, $this->encoding);
        return $this;
    }
    
    /**
     * 
     * @param unknown $start
     * @param string $length
     * @return \X\Library\XData\XString
     */
    public function sub($start, $length=null) {
        if ( $this->getLength() > ($start + (is_null($length) ? 0 : $length)) ) {
            $this->string = mb_substr($this->string, $start, $length, $this->encoding);
        }
        return $this;
    }
    
    /**
     *
     * @return mixed
     */
    public function getLength() {
        return mb_strlen($this->string, $this->encoding);
    }
    
    /**
     *
     * @return boolean
     */
    public function isEmpty() {
        return 0 == $this->getLength();
    }
    
    /**
     * 
     * @param unknown $string
     * @return mixed
     */
    public static function toHttpTagAttributeValue($string) {
        $string = str_replace('"', '&quot;', $string);
        return $string;
    }
    
    /**
     * Generate a UUID string
     *
     * @return string
     */
    public static function generateUUID() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
    }
    
    /**
     * Format string value to safty use in javascript.
     * 
     * @param string $string
     * @return string
     */
    public static function toJavascriptString( $string ) {
        $string = str_replace('"', '&quot;', $string);
        return $string;
    }
    
    /**
     * Convert all applicable characters to HTML entities
     * 
     * @param string $string The input string
     * @param string $style The style mark
     * @return string
     */
    public static function htmlEntities( $string, $style=ENT_QUOTES ) {
        return htmlentities($string, $style);
    }
    
    /**
     * Convert special characters to HTML entities
     * 
     * @param string $string The input string
     * @param string $style The style marks
     * @return string
     */
    public static function htmlSpecialChars($string, $style=ENT_QUOTES ) {
        return htmlspecialchars($string, $style, 'UTF-8');
    }
    
    /**
     * Convert new line to html <br/> mark.
     * 
     * @param string $string The input string.
     * 
     * @return mixed
     */
    public static function nl2Char( $string ) {
        if ( false !== strpos($string, "\r\n") ) {
            return str_replace("\r\n", '\\n', $string);
        } else if ( false !== strpos($string, "\r") ) {
            return str_replace("\r", '\\n', $string);
        } else {
            return str_replace("\n", '\\n', $string);
        }
    }
    
    /**
     * Get a rand string .
     * 
     * @param integer $length The length of rand string.
     * @return string
     */
    public static function randString($length) {
        $letters = str_split('qwertyuipasdfghjklzxcvbnm123456789');
        $code = array();
        $codeLength = $length;
        $letterLenght = count($letters)-1;
        for ( $i=0; $i<$codeLength; $i++ ) {
            $code[$i] = $letters[rand(0, $letterLenght)];
        }
        return implode('', $code);
    }
}
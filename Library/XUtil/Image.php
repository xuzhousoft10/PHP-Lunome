<?php
/**
 * Namespace
 */
namespace X\Library\XUtil;

/**
 * Image class
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Versino 0.0.0
 */
class Image extends \X\Core\Basic {
    /**
     * The image resource
     * 
     * @var resource
     */
    protected $image = null;
    
    /**
     * Initiate the image.
     * 
     * @param integer $height
     * @param integer $width
     */
    public function __construct( $height, $width ) {
        $this->image = imagecreatetruecolor($width, $height);
    }
    
    /**
     * The activated color number.
     * 
     * @var number
     */
    protected $activatedColor = null;
    
    /**
     * Allocate a color for an image
     * 
     * @param unknown $red
     * @param unknown $green
     * @param unknown $blue
     * @return \X\Library\XUtil\Image
     */
    public function setColor( $red, $green, $blue ) {
        $this->activatedColor = imagecolorallocate($this->image, $red, $green, $blue);
        return $this;
    }
    
    /**
     * Flood fill
     * 
     * @param unknown $x
     * @param unknown $y
     * @return \X\Library\XUtil\Image
     */
    public function fill($x, $y) {
        imagefill($this->image, $x, $y, $this->activatedColor);
        return $this;
    }
    
    /**
     * Draw a rectangle
     * 
     * @param unknown $x1
     * @param unknown $y1
     * @param unknown $x2
     * @param unknown $y2
     * @return \X\Library\XUtil\Image
     */
    public function rectangle($x1, $y1, $x2, $y2) {
        imagerectangle($this->image, $x1, $y1, $x2, $y2, $this->activatedColor);
        return $this;
    }
    
    /**
     * Draw a line
     * 
     * @param unknown $x1
     * @param unknown $y1
     * @param unknown $x2
     * @param unknown $y2
     * @return \X\Library\XUtil\Image
     */
    public function line($x1, $y1, $x2, $y2) {
        imageline($this->image, $x1, $y1, $x2, $y2, $this->activatedColor);
        return $this;
    }
    
    /**
     * Draw a character horizontally
     * 
     * @param unknown $font
     * @param unknown $x
     * @param unknown $y
     * @param unknown $c
     * @return \X\Library\XUtil\Image
     */
    public function addChar($font, $x, $y, $c) {
        imagechar($this->image, $font, $x, $y, $c, $this->activatedColor);
        return $this;
    }
    
    /* image formations */
    const FORMAT_PNG = 'png';
    
    /**
     * Save or out put image.
     * 
     * @param unknown $type
     * @param string $path
     * @return \X\Library\XUtil\Image
     */
    public function save( $type=self::FORMAT_PNG, $path=null ) {
        $handler = sprintf('image%s', $type);
        if ( is_null($path) ) {
            header(sprintf('Content-type: image/%s', $type));
            $handler($this->image);
        } else {
            $handler($this->image, $path);
        }
        return $this;
    }
    
    /**
     * Destroy an image
     * 
     * @return void
     */
    public function destroy() {
        imagedestroy($this->image);
    }
}
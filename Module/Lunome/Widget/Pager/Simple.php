<?php
namespace X\Module\Lunome\Widget\Pager;
/**
 * 
 */
class Simple extends Basic {
    /**
     * @var array
     */
    private $centerViews = array();
    
    /**
     * @param string $content
     * @return \X\Module\Lunome\Widget\Pager\Simple
     */
    public function addViewToCenter( $content ) {
        $this->centerViews[] = $content;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCenterViewContents() {
        return implode("\n", $this->centerViews);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Widget\Basic::toString()
     */
    public function toString() {
        ob_start();
        ob_implicit_flush(false);
        require dirname(__FILE__).DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.'Simple.php';
        return ob_get_clean();
    }
}
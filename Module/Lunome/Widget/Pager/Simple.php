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
        if ( $this->isPageInformationEnabled() && (1<($totalPage = $this->getTotalPageNumber())) ) {
            $this->addViewToCenter(sprintf('<li>%d/%d</li>', $this->currentPage, $totalPage));
        }
        return implode("\n", $this->centerViews);
    }
    
    /**
     * @var boolean
     */
    private $displayPageInformation = false;
    
    /**
     * @return \X\Module\Lunome\Widget\Pager\Simple
     */
    public function enablePageInformation() {
        $this->displayPageInformation = true;
        return $this;
    }
    
    /**
     * @return \X\Module\Lunome\Widget\Pager\Simple
     */
    public function disablePageInformation() {
        $this->displayPageInformation = false;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function isPageInformationEnabled() {
        return $this->displayPageInformation;
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
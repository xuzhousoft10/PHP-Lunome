<?php
namespace X\Module\Lunome\Widget\Pager;
/**
 * 
 */
use X\Util\Widget\Basic as WidgetBasic;

/**
 * 
 */
abstract class Basic extends WidgetBasic {
    /**
     * @var integer
     */
    protected $totalNumber = 0;
    
    /**
     * @param integer $number
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setTotalNumber( $number ) {
        $this->totalNumber = $number;
        return $this;
    }
    
    /**
     * @var integer
     */
    protected $pageSize = 1;
    
    /**
     * @param integer $pageSize
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setPageSize( $pageSize ) {
        $this->pageSize = $pageSize;
        return $this;
    }
    
    /**
     * @var integer
     */
    protected $currentPage = 1;
    
    /**
     * @param integer $pageNumber
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setCurrentPage( $pageNumber ) {
        $this->currentPage = $pageNumber;
        return $this;
    }
    
    /**
     * @var string
     */
    protected $url = null; 
    
    /**
     * @param string $url
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setPagerURL ( $url ){
        $this->url = $url;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function isPrevPageAvailable() {
        return 1 < $this->currentPage;
    }
    
    /**
     * @return boolean
     */
    public function isNextPageAvailabel() {
        return $this->pageSize*$this->currentPage < $this->totalNumber;
    }
    
    /**
     * @return number
     */
    public function getTotalPageNumber() {
        $total = (int)($this->totalNumber/$this->pageSize);
        return (0===$this->totalNumber%$this->pageSize) ? $total : $total+1;
    }
    
    /**
     * @return string
     */
    public function getPrevPageURL(){
        if ( !$this->isPrevPageAvailable() ) {
            return '#';
        } else {
            return str_replace('%7B%24page%7D', $this->currentPage-1, $this->url);
        }
    }
    
    /**
     * @return string
     */
    public function getNextPageURL(){
        if ( !$this->isNextPageAvailabel() ) {
            return '#';
        } else {
            return str_replace('%7B%24page%7D', $this->currentPage+1, $this->url);
        }
    }
    
    /**
     * @var string
     */
    protected $prevPageButtonClass = null;
    
    /**
     * @param string $className
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setPrevPageButtonClass( $className ) {
        $this->prevPageButtonClass = $className;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPrevPageButtonClass() {
        return $this->prevPageButtonClass;
    }
    
    /**
     * @var string
     */
    protected $nextPageButtonClass = null;
    
    /**
     * @param string $className
     * @return \X\Module\Lunome\Widget\Pager\Basic
     */
    public function setNextPageButtonClass( $className ) {
        $this->nextPageButtonClass = $className;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNextPageButtonClass() {
        return $this->nextPageButtonClass;
    }
}
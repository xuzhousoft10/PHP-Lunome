<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * Use statements
 */
use X\Library\XMath\Number;

/**
 * Visual action class
 */
abstract class VisualMainMediaList extends VisualMain {
    protected $currentMark = 0;
    protected $currentPage = 1;
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::afterRunAction()
     */
    protected function afterRunAction() {
        $this->activeMenuItem($this->getActiveMenuItem());
        
        $medias     = $this->getMediaData();
        $markInfo   = $this->getMarkInformation();
        $total      = $markInfo[$this->currentMark];
        $pager      = $this->getPagerData($total);
        $markInfo['active'] = $this->currentMark;
        
        /* Load index view */
        $name   = 'MOVIE_INDEX';
        $path   = $this->getMediaIndexView();
        $option = array();
        $data   = array('medias'=>$medias, 'markInfo'=>$markInfo, 'pager'=>$pager);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        parent::afterRunAction();
    }
    
    /**
     * 
     * @return array
     */
    protected function getMediaData() {
        $pageSize   = $this->getPageSize();
        $condition  = array();
        $length     = $pageSize;
        $position   = $pageSize * ($this->currentPage-1);
        if ( empty($this->currentMark) ) {
            $medias = $this->getMediaService()->getUnmarked($condition, $length, $position);
        } else {
            $medias = $this->getMediaService()->getMarked($this->currentMark, $length, $position);
        }
        
        return $medias;
    }
    
    /**
     * Setup the pager data.
     * 
     * @param unknown $current
     * @param unknown $total
     * @param unknown $params
     */
    protected function getPagerData($total) {
        $current    = $this->currentPage;
        $params     = array('mark'=>$this->currentMark);
        
        $pageSize   = $this->getPageSize();
        $total      = (0!=$total && 0===$total%$pageSize) ? $total/$pageSize : intval($total/$pageSize)+1;
        
        $pager = array();
        $pager['current']   = $current;
        $pager['total']     = $total;
        $pager['canPrev']   = ( 1 < $current );
        $pager['canNext']   = ( $total > $current );
        $pager['prev']      = $pager['canPrev'] ? $current-1 : 1;
        $pager['next']      = $pager['canNext'] ? $current + 1 : $total;
        $pager['items']     = Number::getRound($current, $this->getPageItemCount(), 1, $total);
        $pager['params']    = $params;
        return $pager;
    }
    
    protected function getPageSize() {
        return 20;
    }
    
    protected function getPageItemCount() {
        return 15;
    }
    
    /**
     * @return \X\Module\Lunome\Util\Service\Media
     */
    abstract protected function getMediaService();
    
    /**
     * @return array
     */
    abstract protected function getMarkInformation();
    
    /**
     * 
     */
    abstract protected function getMediaIndexView();
    
    /**
     * 
     */
    abstract protected function getActiveMenuItem();
}
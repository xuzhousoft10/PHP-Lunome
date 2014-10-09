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
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->setDataToParticle($this->pagerData['view'], 'pager', $this->pagerData);
        parent::afterRunAction();
    }
    
    /**
     * @var array
     */
    protected $pagerData = array();
    
    /**
     * Setup the pager data.
     * 
     * @param unknown $current
     * @param unknown $total
     * @param unknown $params
     */
    protected function setPager( $view, $current, $total, $params=array() ) {
        $pageSize = $this->getPageSize();
        $total = (0!=$total && 0===$total%$pageSize) ? $total/$pageSize : intval($total/$pageSize)+1;
        
        $pager = array();
        $pager['view']      = $view;
        $pager['current']   = $current;
        $pager['total']     = $total;
        $pager['canPrev']   = ( 1 < $current );
        $pager['canNext']   = ( $total > $current );
        $pager['prev']      = $pager['canPrev'] ? $current-1 : 1;
        $pager['next']      = $pager['canNext'] ? $current + 1 : $total;
        $pager['items']     = Number::getRound($current, $this->getPageItemCount(), 1, $total);
        $pager['params']    = $params;
        $this->pagerData    = $pager;
    }
    
    protected function getPageSize() {
        return 20;
    }
    
    protected function getPageItemCount() {
        return 15;
    }
}
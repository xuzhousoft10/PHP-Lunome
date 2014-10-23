<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action;

/**
 * 
 */
abstract class Index extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        /* Load index layout. */
        $this->getView()->loadLayout($this->getLayoutViewPath('Index'));
    }
}
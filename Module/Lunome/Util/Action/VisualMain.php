<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * Visual action class
 */
abstract class VisualMain extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        $layout = $this->getLayoutViewPath('Main');
        $this->getView()->loadLayout($layout);
    }
}
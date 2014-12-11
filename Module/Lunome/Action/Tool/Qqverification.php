<?php
/**
 * The action file for tool/qqverification action.
 */
namespace X\Module\Lunome\Action\Tool;

/**
 * 
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

/**
 * The action class for tool/qqverification action.
 * @author Unknown
 */
class Qqverification extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction() {
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN);
        $this->getView()->addOpenGraphData('qq-verification', 'qc:admins', '5241260046755655206077');
    }
    
    protected function afterRunAction() {
        $this->getView()->display();
    }
}
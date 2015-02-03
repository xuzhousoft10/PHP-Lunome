<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Tool;

/**
 * use statements
 */
use X\Util\Action\Visual;
use X\Service\XView\Core\Handler\Html;

/**
 * The action class for tool/qqverification action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Qqverification extends Visual { 
    /**
     * @return void
     */
    public function runAction() {
        $this->getView()->loadLayout(Html::LAYOUT_SINGLE_COLUMN);
        $this->getView()->addOpenGraphData('qq-verification', 'qc:admins', '5241260046755655206077');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->display();
    }
}
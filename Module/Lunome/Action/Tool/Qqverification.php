<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Tool;

/**
 * use statements
 */
use X\Util\Action\Visual;

/**
 * The action class for tool/qqverification action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Qqverification extends Visual { 
    /**
     * @return void
     */
    public function runAction() {
        $this->getView()->setLayout($this->getLayoutViewPath('Blank'));
        $this->getView()->getMetaManager()->setOGAdmins('5241260046755655206077','qc:admins');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->display();
    }
}
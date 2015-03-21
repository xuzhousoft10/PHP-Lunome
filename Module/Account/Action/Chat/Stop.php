<?php
namespace X\Module\Account\Action\Chat;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;
/**
 * 
 */
class Stop extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        $friendManager = $this->getCurrentAccount()->getFriendManager();
        if ( !$friendManager->isFriendWith($id) ) {
            return;
        }
        $friendManager->get($id)->getChatManager()->stop();
        echo json_encode(array('status'=>'1'));
    }
}
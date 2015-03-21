<?php
namespace X\Module\Account\Action\Chat;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Read extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friend ) {
        $currentAccount = $this->getCurrentAccount();
        if ( !$currentAccount->getFriendManager()->isFriendWith($friend) ) {
            return;
        }
        
        $messages = $currentAccount->getFriendManager()->get($friend)->getChatManager()->read();
        
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $friendInformation = $accountService->get($friend)->getProfileManager();
        
        $name   = 'USER_FRIEND_SAYS';
        $path   = $this->getParticleViewPath('Chat/FriendSays');
        $option = array();
        $data   = array('messages'=>$messages, 'friendInformation'=>$friendInformation);
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}
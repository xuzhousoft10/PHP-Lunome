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
class Index extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $friend ) {
        $currentAccount = $this->getCurrentAccount();
        $friendManager = $currentAccount->getFriendManager();
        if ( !$friendManager->isFriendWith($friend) ) {
            $this->throw404();
        }
        
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $friend = $accountService->get($friend);
        $friendProfile = $friend->getProfileManager();
        
        $name   = 'USER_CHAT'; 
        $path   = $this->getParticleViewPath('Chat/Index');
        $option = array();
        $data   = array('friend'=>$friendProfile);
        $this->loadParticle($name, $path, $option, $data);
        
        $friendManager->get($friend->getID())->getChatManager()->start();
        
        $this->getView()->setLayout($this->getLayoutViewPath('BlankThin'));
        $this->getView()->title = '与'.$friendProfile->get('nickname').'聊天中';
    }
}
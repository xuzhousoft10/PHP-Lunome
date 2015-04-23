<?php
namespace X\Module\Account\Action\Friend;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * The action class for user/friend/index action.
 * @author Unknown
 */
class GetList extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        $this->checkLoginRequirement();
        
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $friendManager = $this->getCurrentAccount()->getFriendManager();
        $friends = $friendManager->find();
        
        foreach ( $friends as $index => $friend ) {
            $friends[$index] = $friend->getProfileManager()->toArray();
        }
        
        return $this->success('', $friends);
    }
}
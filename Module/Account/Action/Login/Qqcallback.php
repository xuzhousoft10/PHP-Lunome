<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Core\X;
use X\Service\QQ\Service as QQService;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Qqcallback extends \X\Util\Action\Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $QQService QQService */
        $QQService = X::system()->getServiceManager()->get(QQService::getServiceName());
        $QQConnect = $QQService->getConnect();
        
        $QQConnect->setup();
        $tokenInfo = $QQConnect->getTokenInfo();
        
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $accountService->getByOpenID('QQ', array('openid'=>'568109749'));
        
        $this->gotoURL('/index.php');
    }
}
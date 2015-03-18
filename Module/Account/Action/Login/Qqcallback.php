<?php
namespace X\Module\Account\Action\Login;
/**
 * 
 */
use X\Core\X;
use X\Service\QQ\Service as QQService;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Service\QQ\Core\Connect\Exception;
use X\Module\Account\Service\Account\Core\Manager\ProfileManager;
/**
 * 
 */
class Qqcallback extends \X\Util\Action\Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        if ( null !== $accountService->getCurrentAccount() ) {
            $this->gotoURL('index.php');
        }
        
        /* @var $QQService QQService */
        $QQService = X::system()->getServiceManager()->get(QQService::getServiceName());
        $QQConnect = $QQService->getConnect();
        
        try {
            $QQConnect->setup();
            $tokenInfo = $QQConnect->getTokenInfo();
        } catch (Exception $e ) {
            $this->gotoURL('/index.php?module=account&action=login/index');
        }
        
        $openID = $QQConnect->getOpenId();
        $account = $accountService->getByOpenID('QQ', $openID);
        if ( null === $account ) {
            $openIDInfo = array();
            $openIDInfo['openid'] = $openID;
            $openIDInfo['access_token'] = $tokenInfo['access_token'];
            $openIDInfo['refresh_token'] = $tokenInfo['refresh_token'];
            $openIDInfo['expired_at'] = $tokenInfo['expires_in'];
            $account = $accountService->enableByOpenIDInfo('QQ', $openIDInfo);
            
            $userInfo = $QQConnect->QZone()->getInfo();
            
            $profile = $account->getProfileManager();
            $profile->set('nickname', $userInfo['nickname']);
            $profile->set('photo', $userInfo['figureurl_qq_2']);
            if ( '男' === $userInfo['gender'] ) {
                $profile->set('sex', ProfileManager::SEX_MALE);
            } else if ( '女' === $userInfo['gender'] ) {
                $profile->set('sex', ProfileManager::SEX_FEMALE);
            }
            $profile->save();
        }
        
        $account->login();
    }
}
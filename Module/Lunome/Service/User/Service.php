<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Model\Account\AccountOauth20Model;
use X\Module\Lunome\Model\Account\AccountModel;
use X\Service\XDatabase\Core\SQL\Func\Rand;
use X\Module\Lunome\Model\Account\AccountLoginHistoryModel;
use X\Library\XUtil\Network;
use X\Service\QQ\Service as QQService;
use X\Service\Sina\Service as SinaService;
use X\Module\Lunome\Model\Account\AccountNotificationModel;
use X\Module\Lunome\Model\Account\AccountInformationModel;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        if ( isset($_SERVER['HTTP_HOST']) && 'lunome.kupoy.com' === $_SERVER['HTTP_HOST'] && $this->getIsGuest()) {
            if ( false !== strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'firefox') ) {
                $account = AccountModel::model()->find(array('account'=>'0'));
            } else {
                $account = AccountModel::model()->find(array('account'=>'1'));
            }
            $this->loginAccount($account, 'DEBUG');
        }
        $this->initCurrentUserInformation();
    }
    
    /**
     * 初始化用户会话信息， 如果找不到， 则作为游客进行初始化。
     * 
     * @return void
     */
    protected function initCurrentUserInformation() {
        if ( !isset($_SESSION['LUNOME']) ) {
            $_SESSION['LUNOME'] = array();
        }
        if ( !isset($_SESSION['LUNOME']['USER']) ) {
            $_SESSION['LUNOME']['USER'] = array();
            $_SESSION['LUNOME']['USER']['IDENTITY'] = self::UI_GUEST;
        }
    }
    
    /**
     * 获取当前用户是否为游客。
     * 
     * @return boolean
     */
    public function getIsGuest() {
        $isGuest = !isset($_SESSION['LUNOME']);
        $isGuest = $isGuest || !isset($_SESSION['LUNOME']['USER']);
        $isGuest = $isGuest || !isset($_SESSION['LUNOME']['USER']['IDENTITY']);
        $isGuest = $isGuest || self::UI_GUEST === $_SESSION['LUNOME']['USER']['IDENTITY'];
        return $isGuest;
    }
    
    /**
     * 获取当前用户信息
     * 
     * @return array
     */
    public function getCurrentUser() {
        return $_SESSION['LUNOME']['USER'];
    }
    
    /**
     * @return string
     */
    public function getCurrentUserId() {
        if ( $this->getIsGuest() ) {
            return null;
        } else {
            $user = $this->getCurrentUser();
            return $user['ID'];
        }
    }
    
    /**
     * 通过QQConnectSDK登录, 注意， 该方法回直接跳转到登录页面， 你应该在调用完该方法后立即结束。
     * 
     * @return void
     */
    public function getQQLoginURL() {
        $qqConnect = $this->getQQConnect();
        return $qqConnect->getLoginUrl();
    }
    
    /**
     * QQ Connect 登录的回调处理方法。
     * 
     * @return void
     */
    public function loginByQQCallBack() {
        $qqConnect = $this->getQQConnect();
        $qqConnect->setup();
        $token = $qqConnect->getTokenInfo();
        
        $openId = $qqConnect->getOpenId();
        $condition = array('server'=>AccountOauth20Model::SERVER_QQ, 'openid'=>$openId);
        $oauth = AccountOauth20Model::model()->find($condition);
        if ( null === $oauth ) {
            $oauth = new AccountOauth20Model();
            $oauth->server = AccountOauth20Model::SERVER_QQ;
            $oauth->openid = $qqConnect->getOpenId();
        }
        $oauth->access_token    = $token['access_token'];
        $oauth->expired_at      = $token['expires_in'];
        $oauth->refresh_token   = $token['refresh_token'];
        $oauth->save();
        $account = $this->getAccountByOAuth($oauth);
        $this->loginAccount($account, 'QQ OAuth2.0');
    }
    
    /**
     * 获取微博登录的URL
     * @param unknown $callbackURL
     * @return Ambigous <multitype:, string>
     */
    public function getWeiboLoginURL( $callbackURL ) {
        $url = $this->getWeiboConnect()->getAuthorizeURL($callbackURL);
        return $url;
    }
    
    /**
     * @return void
     */
    public function loginByWeiboCallback( $code, $callbackURL ) {
        $connect = $this->getWeiboConnect();
        $connect->handleCallbackByCode($code, $callbackURL);
        $token = $connect->accessToken;
        $openId = $connect->uid;
        
        $condition = array('server'=>AccountOauth20Model::SERVER_SINA, 'openid'=>$openId);
        $oauth = AccountOauth20Model::model()->find($condition);
        if ( null === $oauth ) {
            $oauth = new AccountOauth20Model();
            $oauth->server = AccountOauth20Model::SERVER_SINA;
            $oauth->openid = $openId;
        }
        $oauth->access_token    = $token;
        $oauth->expired_at      = date('Y-m-d H:i:s', time()+$connect->accessTokenLifeTime);
        $oauth->refresh_token   = '';
        $oauth->save();
        $account = $this->getAccountByOAuth($oauth);
        $this->loginAccount($account, 'Sina OAuth2.0');
    }
    
    /**
     * @var QQService
     */
    private $qqService = null;
    
    /**
     * 获取QQConnect 的 handler
     * @return \X\Service\QQ\Core\Connect\SDK
     */
    public function getQQConnect() {
        if ( null === $this->qqService ) {
            $this->qqService = X::system()->getServiceManager()->get(QQService::getServiceName());
            if ( !$this->getIsGuest() ) {
                $account = AccountModel::model()->find(array('id'=>$_SESSION['LUNOME']['USER']['ID']));
                $oauth = $this->getAccount()->getOauth($account->oauth20_id);
                if ( empty($oauth) ) {
                    return false;
                }
                $this->qqService->getConnect()->setOpenId($oauth['openid']);
                $this->qqService->getConnect()->setAccessToken($oauth['access_token']);
            }
        }
        
        return $this->qqService->getConnect();
    }
    
    /**
     * @var SinaService
     */
    private $sinaService = null;
    
    /**
     * @return \X\Service\Sina\Core\Connect\SDK
     */
    public function getWeiboConnect() {
        if ( null === $this->sinaService ) {
            $this->sinaService = X::system()->getServiceManager()->get(SinaService::getServiceName());
            if ( !$this->getIsGuest() ) {
                $oauth = $this->getAccount()->getOauth();
                if ( empty($oauth) ) {
                    return false;
                }
                $this->sinaService->getConnect()->accessToken = $oauth['access_token'];
                $this->sinaService->getConnect()->uid = $oauth['openid'];
            }
        }
        return $this->sinaService->getConnect();
    }
    
    /**
     * 
     * @param Oauth20Model $oauth
     * @return Ambigous <\X\Module\Lunome\Model\AccountModel, \X\Service\XDatabase\Core\ActiveRecord\ActiveRecord, NULL>
     */
    protected function getAccountByOAuth( AccountOauth20Model $oauth ) {
        $account = AccountModel::model()->find(array('oauth20_id'=>$oauth->id));
        
        $information = array();
        if ( AccountOauth20Model::SERVER_QQ === $oauth->server ) {
            $connect = $this->getQQConnect();
            $userInfo = $connect->QZone()->getInfo();
            $information['nickname'] = $userInfo['nickname'];
            $information['photo'] = $userInfo['figureurl_qq_2'];
        } else if ( AccountOauth20Model::SERVER_SINA === $oauth->server ) {
            $connect = $this->getWeiboConnect();
            $userInfo = $connect->User()->getInfo();
            $information['nickname'] = $userInfo['name'];
            $information['photo'] = $userInfo['avatar_large'];
        }
        
        if ( null === $account ) {
            $account = $this->enableRandomAccount();
        }
        
        $account->oauth20_id    = $oauth->id;
        $account->save();
        
        $accountInformation = new AccountInformationModel();
        $accountInformation->account_id = $account->id;
        $accountInformation->account_number = $account->account;
        $accountInformation->nickname = $information['nickname'];
        $accountInformation->photo = $information['photo'];
        return $account;
    }
    
    /**
     * 
     * @return \X\Module\Lunome\Model\AccountModel
     */
    protected function enableRandomAccount() {
        $condition = array('status'=>AccountModel::ST_NOT_USED);
        $accounts = AccountModel::model()->findAll($condition, 1, 0, array(new Rand()));
        
        /* 创建空帐号， 并且在这些空帐号里随机选择将要使用的帐号。 */
        /* 如果是首次创建帐号， 那么， 第一个使用的帐号将作为管理员帐号。 */
        if ( 0 === count($accounts) ) {
            $isFirstTime = false;
            $randomIndex = rand(0, 9);
            $randomAccount = null;
            $startAccount = AccountModel::model()->getMax('account');
            if ( null === $startAccount || 1000 > $startAccount ) {
                $startAccount = 1000;
                $isFirstTime = true;
            }
            
            for( $i=0; $i<10; $i++ ) {
                $tmpAccount = new AccountModel();
                $tmpAccount->account = $startAccount + $i + 1;
                $tmpAccount->status = AccountModel::ST_NOT_USED;
                $tmpAccount->save();
                if ( $randomIndex === $i ) {
                    $randomAccount = $tmpAccount;
                } else {
                    $tmpAccount = null;
                    unset($tmpAccount);
                }
            }
            $account = $randomAccount;
            $account->is_admin = AccountModel::IS_ADMIN_YES;
        } else {
            $account = $accounts[0];
        }
        
        /* @var $account \X\Module\Lunome\Model\AccountModel */
        $account->status = AccountModel::ST_IN_USE;
        $account->enabled_at = date('Y-m-d H:i:s', time());
        $account->save();
        
        $this->getAccount()->logAction(Account::ACTION_ENABLE, $account->id, Account::RESULT_CODE_SUCCESS, null, null, $account->id);
        return $account;
    }
    
    /**
     * 
     * @param AccountModel $account
     */
    protected function loginAccount( AccountModel $account, $loginedBy ) {
        $information = AccountInformationModel::model()->find(array('account_id'=>$account->id));
        $_SESSION['LUNOME']['USER']['ID']           = $account->id;
        $_SESSION['LUNOME']['USER']['ACCOUNT']      = $account->account;
        $_SESSION['LUNOME']['USER']['NICKNAME']     = $information->nickname;
        $_SESSION['LUNOME']['USER']['PHOTO']        = $information->photo;
        $_SESSION['LUNOME']['USER']['OAUTH20ID']    = $account->oauth20_id;
        $_SESSION['LUNOME']['USER']['IDENTITY']     = self::UI_NORMAL;
        $_SESSION['LUNOME']['USER']['IS_ADMIN']     = $account->is_admin == AccountModel::IS_ADMIN_YES;
        $_SESSION['LUNOME']['USER']['LOGIN_BY']     = $loginedBy;
        $_SESSION['LUNOMT']['USER']['INFORMATION']  = $information->toArray();
        $this->recordLoginHistory($account, $loginedBy);
    }
    
    /**
     * 
     * @param AccountModel $account
     * @param unknown $loginedBy
     */
    protected function recordLoginHistory( AccountModel $account, $loginedBy ) {
        $ip = Network::getClientIP();
        $ipInfo = Network::IpInfo($ip, 'china');
        $loginHistory = new AccountLoginHistoryModel();
        $loginHistory->account_id   = $account->id;
        $loginHistory->time         = date('Y-m-d H:i:s', time());
        $loginHistory->ip           = $ip;
        $loginHistory->country      = $ipInfo['country'];
        $loginHistory->province     = $ipInfo['province'];
        $loginHistory->city         = $ipInfo['city'];
        $loginHistory->isp          = $ipInfo['isp'];
        $loginHistory->logined_by   = $loginedBy;
        $loginHistory->save();
        
        $this->getAccount()->logAction(Account::ACTION_LOGIN, $account->id, Account::RESULT_CODE_SUCCESS);
    }
    
    /**
     * Logout current user.
     */
    public function logout() {
        $_SESSION['LUNOME']['USER'] = null;
        unset($_SESSION['LUNOME']['USER']);
        session_destroy();
        setcookie(session_name(),'',time()-3600);
    }
    
    /**
     * @var \X\Module\Lunome\Service\User\Account
     */
    private $account = null;
    
    /**
     * @return \X\Module\Lunome\Service\User\Account
     */
    public function getAccount() {
        if ( null === $this->account ) {
            $this->account = new Account();
        }
        return $this->account;
    }
    
    /**
     * @param unknown $view
     * @param unknown $sourceModel
     * @param unknown $sourceId
     * @param unknown $recipiendId
     */
    public function sendNotification( $view, $sourceModel, $sourceId, $recipiendId ) {
        $notification = new AccountNotificationModel();
        $notification->created_at   = date('Y-m-d H:i:s');
        $notification->produced_by  = $this->getCurrentUserId();
        $notification->view         = $view;
        $notification->source_model = $sourceModel;
        $notification->source_id    = $sourceId;
        $notification->recipient_id = $recipiendId;
        $notification->save();
    }
    
    /**
     * @return number
     */
    public function countUnclosedNotification() {
        $condition = array();
        $condition['status']        = AccountNotificationModel::STATUS_NEW;
        $condition['produced_by']   = $this->getCurrentUserId();
        $count = AccountNotificationModel::model()->count($condition);
        return $count;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Account\AccountNotificationModel []
     */
    public function getUnclosedNotifications() {
        $condition = array();
        $condition['status']        = AccountNotificationModel::STATUS_NEW;
        $condition['produced_by']   = $this->getCurrentUserId();
        $notifications = AccountNotificationModel::model($condition)->findAll($condition);
        foreach ( $notifications as $index => $notification ) {
            $notifications[$index] = $notification->toArray();
            $sourceModel = $notification->source_model;
            $sourceModel = $sourceModel::model()->findByPrimaryKey($notification->source_id);
            $notifications[$index]['sourceData'] = $sourceModel->toArray();
        }
        return $notifications;
    }
    
    public function getNotification( $id ) {
        $notification = AccountNotificationModel::model()->findByPrimaryKey($id);
        $notification = $notification->toArray();
        $sourceModel = $notification['source_model'];
        $sourceModel = $sourceModel::model()->findByPrimaryKey($notification['source_id']);
        $notification['sourceData'] = $sourceModel->toArray();
        return $notification;
    }
    
    /**
     * @param unknown $notificationID
     */
    public function closeNotification( $notificationID ) {
        $notification = AccountNotificationModel::model()->findByPrimaryKey($notificationID);
        $notification->status = AccountNotificationModel::STATUS_CLOSED;
        $notification->save();
    }
    
//     /**
//      * @var \X\Module\Lunome\Service\User\Friend
//      */
//     private $friend = null;
    
//     /**
//      * 
//      * @return \X\Module\Lunome\Service\User\Friend
//      */
//     public function getFriend() {
//         if ( null === $this->friend ) {
//             $this->friend = new Friend($this);
//         }
//         return $this->friend;
//     }
    
    /* User indentity consts. */
    const UI_GUEST  = 1;
    const UI_NORMAL = 2;
}
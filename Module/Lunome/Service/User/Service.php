<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Module\Lunome\Model\Oauth20Model;
use X\Module\Lunome\Model\AccountModel;
use X\Service\XDatabase\Core\SQL\Func\Rand;
use X\Module\Lunome\Model\AccountLoginHistoryModel;
use X\Library\XUtil\Network;
use X\Library\QQ\Connect\SDK as QQConnectSDK;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        if ( 'lunome.kupoy.com' === $_SERVER['HTTP_HOST'] && $this->getIsGuest()) {
            $account = AccountModel::model()->find(array('status'=>2, 'is_admin'=>1));
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
     * 通过QQConnectSDK登录, 注意， 该方法回直接跳转到登录页面， 你应该在调用完该方法后立即结束。
     * 
     * @return void
     */
    public function loginByQQ() {
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
        $condition = array('server'=>Oauth20Model::SERVER_QQ, 'openid'=>$openId);
        $oauth = Oauth20Model::model()->find($condition);
        if ( null === $oauth ) {
            $oauth = new Oauth20Model();
            $oauth->server = Oauth20Model::SERVER_QQ;
            $oauth->openid = $qqConnect->getOpenId();
        }
        $oauth->access_token    = $token['access_token'];
        $oauth->expired_at      = $token['expires_in'];
        $oauth->refresh_token   = $token['refresh_token'];
        $oauth->save();
        $account = $this->getAccountByOAuth($oauth, $qqConnect);
        $this->loginAccount($account, 'QQ OAuth2.0');
    }
    
    /**
     * 获取QQConnect 的 handler
     * 
     * @return \X\Library\QQ\QQConnectSDK
     */
    protected function getQQConnect() {
        $host = $_SERVER['HTTP_HOST'];
        $callBack = sprintf('http://%s/index.php?module=lunome&action=user/login/qqcallback', $host);
        QQConnectSDK::$appid = '101161224';
        QQConnectSDK::$appkey = 'f03143e996578b9222180fc85d594473';
        QQConnectSDK::$callback = $callBack;
        
        $qqConnect = new QQConnectSDK();
        return $qqConnect;
    }
    
    /**
     * 
     * @param Oauth20Model $oauth
     * @param QQConnectSDK $qqConnect
     * @return Ambigous <\X\Module\Lunome\Model\AccountModel, \X\Service\XDatabase\Core\ActiveRecord\ActiveRecord, NULL>
     */
    protected function getAccountByOAuth( Oauth20Model $oauth, QQConnectSDK $qqConnect ) {
        $account = AccountModel::model()->find(array('oauth20_id'=>$oauth->id));
        $userInfo = $qqConnect->QZone()->getInfo();
        if ( null === $account ) {
            $account = $this->enableRandomAccount();
        }
        
        $account->nickname      = $userInfo['nickname'];
        $account->photo         = $userInfo['figureurl_qq_2'];
        $account->oauth20_id    = $oauth->id;
        $account->save();
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
        $_SESSION['LUNOME']['USER']['ID']           = $account->id;
        $_SESSION['LUNOME']['USER']['ACCOUNT']      = $account->account;
        $_SESSION['LUNOME']['USER']['NICKNAME']     = $account->nickname;
        $_SESSION['LUNOME']['USER']['PHOTO']        = $account->photo;
        $_SESSION['LUNOME']['USER']['OAUTH20ID']    = $account->oauth20_id;
        $_SESSION['LUNOME']['USER']['IDENTITY']     = self::UI_NORMAL;
        $_SESSION['LUNOME']['USER']['IS_ADMIN']     = $account->is_admin == AccountModel::IS_ADMIN_YES;
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
     * @var \X\Module\Lunome\Service\User\Friend
     */
    private $friend = null;
    
    /**
     * 
     * @return \X\Module\Lunome\Service\User\Friend
     */
    public function getFriend() {
        if ( null === $this->friend ) {
            $this->friend = new Friend($this);
        }
        return $this->friend;
    }
    
    /* User indentity consts. */
    const UI_GUEST  = 1;
    const UI_NORMAL = 2;
}
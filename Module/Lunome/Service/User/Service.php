<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\User;

use X\Library\QQ\Connect as QQConnect;
use X\Module\Lunome\Model\Oauth20Model;
use X\Module\Lunome\Model\AccountModel;
use X\Service\XDatabase\Core\SQL\Func\Rand;
/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
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
        return self::UI_GUEST === $_SESSION['LUNOME']['USER']['IDENTITY'];
    }
    
    public function getCurrentUser() {
        return $_SESSION['LUNOME']['USER'];
    }
    
    public function loginByQQ() {
        $qqConnect = $this->getQQConnect();
        $qqConnect->login();
    }
    
    public function loginByQQCallBack() {
        $qqConnect = $this->getQQConnect();
        $qqConnect->setup();
        $token = $qqConnect->getTokenInfo();
        
        $openId = $qqConnect->getOpenId();
        $condition = array('server'=>Oauth20Model::SERVER_QQ, 'openid'=>$openId);
        $oauth = Oauth20Model::model()->findByAttribute($condition);
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
        $this->loginAccount($account);
    }
    
    protected function getQQConnect() {
        $host = $_SERVER['HTTP_HOST'];
        $callBack = sprintf('http://%s/index.php?module=lunome&action=user/login/qqcallback', $host);
        QQConnect::$appid = '101161224';
        QQConnect::$appkey = 'f03143e996578b9222180fc85d594473';
        QQConnect::$callback = urlencode($callBack);
        
        $qqConnect = new QQConnect();
        return $qqConnect;
    }
    
    protected function getAccountByOAuth( Oauth20Model $oauth, QQConnect $qqConnect ) {
        $account = AccountModel::model()->findByAttribute(array('oauth20_id'=>$oauth->id));
        $userInfo = $qqConnect->getUserInfo();
        if ( null === $account ) {
            $account = $this->enableRandomAccount();
        }
        
        $account->nickname      = $userInfo['nickname'];
        $account->photo         = $userInfo['figureurl_qq_2'];
        $account->oauth20_id    = $oauth->id;
        $account->save();
        return $account;
    }
    
    protected function enableRandomAccount() {
        $condition = array('status'=>AccountModel::ST_NOT_USED);
        $accounts = AccountModel::model()->findAll($condition, 1, 0, array(new Rand()));
        if ( 0 === count($accounts) ) {
            $randomIndex = rand(0, 9);
            $randomAccount = null;
            /* Prepare more accounts */
            $startAccount = AccountModel::model()->getMax('account');
            if ( null === $startAccount || 1000 > $startAccount ) {
                $startAccount = 1000;
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
        } else {
            $account = $accounts[0];
        }
        
        /* @var $account \X\Module\Lunome\Model\AccountModel */
        $account->status = AccountModel::ST_IN_USE;
        $account->enabled_at = date('Y-m-d H:i:s', time());
        $account->save();
        return $account;
    }
    
    protected function loginAccount( AccountModel $account ) {
        $_SESSION['LUNOME']['USER']['ID']           = $account->id;
        $_SESSION['LUNOME']['USER']['ACCOUNT']      = $account->account;
        $_SESSION['LUNOME']['USER']['NICKNAME']     = $account->nickname;
        $_SESSION['LUNOME']['USER']['PHOTO']        = $account->photo;
        $_SESSION['LUNOME']['USER']['OAUTH20ID']    = $account->oauth20_id;
        $_SESSION['LUNOME']['USER']['IDENTITY']     = self::UI_NORMAL;
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
<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\User;

use X\Library\QQ\Connect as QQConnect;
use X\Module\Lunome\Model\Oauth20Model;
use X\Module\Lunome\Model\AccountsModel;
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
    
    public function loginByQQ() {
        QQConnect::$appid = '101161224';
        QQConnect::$appkey = 'f03143e996578b9222180fc85d594473';
        QQConnect::$callback = urlencode('http://lunome.kupoy.com/index.php?module=lunome&action=user/login/qqcallback');
        
        $qqConnect = new QQConnect();
        $qqConnect->login();
    }
    
    public function loginByQQCallBack() {
        QQConnect::$appid = '101161224';
        QQConnect::$appkey = 'f03143e996578b9222180fc85d594473';
        QQConnect::$callback = urlencode('http://lunome.kupoy.com/index.php?module=lunome&action=user/login/qqcallback');
        
        $qqConnect = new QQConnect();
        $qqConnect->setup();
        $token = $qqConnect->getTokenInfo();
        
        $openId = $qqConnect->getOpenId();
        $condition = array('server'=>self::OAUTH_SERVER_QQ, 'openid'=>$openId);
        $oauth = Oauth20Model::model()->findByAttribute($condition);
        if ( null === $oauth ) {
            $oauth = new Oauth20Model();
            $oauth->server = self::OAUTH_SERVER_QQ;
            $oauth->openid = $qqConnect->getOpenId();
        }
        $oauth->access_token    = $token['access_token'];
        $oauth->expired_at      = $token['expires_in'];
        $oauth->refresh_token   = $token['refresh_token'];
        $oauth->save();
        $account = $this->getAccountByOAuthId($oauth->id);
        $this->loginAccount($account);
    }
    
    protected function getAccountByOAuthId( $oauth20Id ) {
        $account = AccountsModel::model()->findByAttribute(array('oauth20_id'=>$oauth20Id));
        if ( null === $account ) {
            $account = $this->enableRandomAccount();
            $account->oauth20_id = $oauth20Id;
            $account->save();
        }
        return $account;
    }
    
    protected function enableRandomAccount() {
        $condition = array('status'=>AccountsModel::ST_NOT_USED);
        $account = AccountsModel::model()->findAll($condition, 1, 0, 'RAND()');
        if ( null === $account ) {
            $randomIndex = rand(0, 10);
            $randomAccount = null;
            /* Prepare more accounts */
            $startAccount = AccountsModel::model()->max('account');
            if ( 0 === $startAccount ) {
                $startAccount = 1000;
            }
            for( $i=0; $i<10; $i++ ) {
                $tmpAccount = new AccountsModel();
                $tmpAccount->account = $startAccount + $i + 1;
                $tmpAccount->status = AccountsModel::ST_NOT_USED;
                $tmpAccount->save();
                if ( $randomIndex === $i ) {
                    $randomAccount = $tmpAccount;
                } else {
                    $tmpAccount = null;
                    unset($tmpAccount);
                }
            }
            $account = $tmpAccount;
        }
        return $account;
    }
    
    protected function loginAccount( AccountsModel $account ) {
        
    }
    
    /* User indentity consts. */
    const UI_GUEST  = 1;
    const UI_NORMAL = 2;
    
    /* OAuthr services */
    const OAUTH_SERVER_QQ = 'qq';
}
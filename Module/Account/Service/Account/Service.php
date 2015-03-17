<?php
namespace X\Module\Account\Service\Account;
/**
 * 
 */
use X\Core\Service\XService;
use X\Module\Account\Service\Account\Core\Model\AccountOauth20Model;
use X\Module\Account\Service\Account\Core\Model\AccountModel;
use X\Module\Account\Service\Account\Core\Instance\Account;
use X\Util\ParameterChecker;
/**
 * 
 */
class Service extends XService {
    /**
     * @var string
     */
    protected static $serviceName = 'Account';
    
    public function find($criteria) {}
    public function count($criteria) {}
    public function getCurrentAccount(){}
    public function get($id) {}
    
    /**
     * @param string $serverName
     * @param array $openIDInfo An array contains the following keys : <br>
     * <li>openid : required</li>
     * <li>access_token : required</li>
     * <li>refresh_token : default null </li>
     * <li>expired_at : default null</li>
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getByOpenID( $serverName, $openIDInfo ) {
        ParameterChecker::check(func_get_args(), __METHOD__)
            ->notEmpty('serverName')
            ->isArray('openIDInfo')
            ->notEmpty('openIDInfo["openid"]');
            
        $validateOpenIDInfo = array('refresh_token'=>null, 'expired_at'=>null);
        $validateOpenIDInfo = array_merge($validateOpenIDInfo, $openIDInfo);
        
        $condition = array('server_name'=>$serverName, 'openid'=>$openIDInfo['openid']);
        /* @var $oauth20 AccountOauth20Model */
        $oauth20 = AccountOauth20Model::model()->find($condition);
        if ( null === $oauth20 ) {
            $account = $this->enableRandomAccount();
            $oauth20->account_id    = $account->id;
            $oauth20->server_name   = $serverName;
            $oauth20->openid        = $validateOpenIDInfo['openid'];
            $oauth20->access_token  = $validateOpenIDInfo['access_token'];
            $oauth20->refresh_token = $validateOpenIDInfo['refresh_token'];
            $oauth20->expired_at    = $validateOpenIDInfo['expired_at'];
            $oauth20->save();
        } else {
            $account = AccountModel::model()->findByPrimaryKey($oauth20->account_id);
        }
        
        $accountInstance = new Account($account);
        return $accountInstance;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Model\AccountModel
     */
    private function enableRandomAccount() {
        return new AccountModel();
    }
}
<?php
namespace X\Module\Account\Service\Account;
/**
 * 
 */
use X\Util\ParameterChecker;
use X\Core\Service\XService;
use X\Service\XDatabase\Core\SQL\Func\Rand;
use X\Module\Account\Service\Account\Core\Instance\Account;
use X\Module\Account\Service\Account\Core\Model\AccountModel;
use X\Module\Account\Service\Account\Core\Model\AccountOauth20Model;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Account\Service\Account\Core\Manager\RegionManager;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
/**
 * 
 */
class Service extends XService {
    /**
     * @var string
     */
    protected static $serviceName = 'Account';
    
    /**
     * @param mixed $condition
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function find($condition) {
        if ( !($condition instanceof Criteria) ) {
            $condition = ConditionBuilder::build($condition);
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        if ( !($condition->condition instanceof ConditionBuilder ) ) {
            $condition->condition = ConditionBuilder::build($condition->condition);
        }
        
        $accounts = AccountModel::model()->findAll($condition);
        foreach ( $accounts as $index => $account ) {
            $accounts[$index] = new Account($account);
        }
        return $accounts;
    }
    
    public function count($criteria) {}
    
    /**
     * @param string $id
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function get($id) {
        $account = AccountModel::model()->findByPrimaryKey($id);
        if ( null === $account ) {
            return null;
        }
        return new Account($account);
    }
    
    /**
     * @param string $id
     * @return boolean
     */
    public function exists( $id ) {
        return AccountModel::model()->exists(array('id'=>$id));
    }
    
    /**
     * @var Account
     */
    private $currentAccount = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getCurrentAccount(){
        $isLogined = isset($_SESSION['lunome']);
        $isLogined = $isLogined && isset($_SESSION['lunome']['user']);
        $isLogined = $isLogined && true===$_SESSION['lunome']['user']['isLogined'];
        if ( !$isLogined ) {
            return null;
        }
        
        if ( null === $this->currentAccount ) {
            $this->currentAccount = new Account($_SESSION['lunome']['user']['id']);
        }
        return $this->currentAccount;
    }
    
    /**
     * @param string $serverName
     * @param array $openID
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getByOpenID( $serverName, $openID ) {
        $condition = array('server_name'=>$serverName, 'openid'=>$openID);
        /* @var $oauth20 AccountOauth20Model */
        $oauth20 = AccountOauth20Model::model()->find($condition);
        if ( null === $oauth20 ) {
            return null;
        } 
        
        $account = AccountModel::model()->findByPrimaryKey($oauth20->account_id);
        $accountInstance = new Account($account);
        return $accountInstance;
    }
    
    /**
     * @param string $serverName
     * @param array $openIDInfo An array contains the following keys : <br>
     * <li>openid : required</li>
     * <li>access_token : required</li>
     * <li>refresh_token : default null </li>
     * <li>expired_at : default null</li>
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function enableByOpenIDInfo( $serverName, $openIDInfo ) {
        ParameterChecker::check(func_get_args(), __METHOD__)
            ->notEmpty('serverName')
            ->isArray('openIDInfo')
            ->notEmpty('openIDInfo["openid"]');
        
        $validateOpenIDInfo = array('refresh_token'=>null, 'expired_at'=>null);
        $validateOpenIDInfo = array_merge($validateOpenIDInfo, $openIDInfo);
        
        $account = $this->enableRandomAccount();
        $oauth20 = new AccountOauth20Model();
        $oauth20->account_id    = $account->id;
        $oauth20->server_name   = $serverName;
        $oauth20->openid        = $validateOpenIDInfo['openid'];
        $oauth20->access_token  = $validateOpenIDInfo['access_token'];
        $oauth20->refresh_token = $validateOpenIDInfo['refresh_token'];
        $oauth20->expired_at    = $validateOpenIDInfo['expired_at'];
        $oauth20->save();
        
        $accountInstance = new Account($account);
        return $accountInstance;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Model\AccountModel
     */
    private function enableRandomAccount() {
        $criteria = new Criteria();
        $criteria->condition = array('status'=>AccountModel::ST_NOT_USED);
        $criteria->limit = 1;
        $criteria->addOrder(new Rand());
        $account = AccountModel::model()->find($criteria);
        
        if ( null === $account ) {
            $account = $this->createPrepareAccount();
        }
        
        /* @var $account \X\Module\Lunome\Model\AccountModel */
        $account->status = AccountModel::ST_IN_USE;
        $account->enabled_at = date('Y-m-d H:i:s', time());
        $account->save();
        return $account;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Model\AccountModel
     */
    private function createPrepareAccount() {
        $account = null;
        $firstAccountNumber = (int)$this->getConfiguration()->get('account_start_number', 1000);
        $isFirstTime = false;
        $startAccount = (int)AccountModel::model()->getMax('account');
        if ( $firstAccountNumber > $startAccount ) {
            $startAccount = $firstAccountNumber;
            $isFirstTime = true;
        }
        
        $prepareAccountCount = (int)$this->getConfiguration()->get('prepare_account_count', 10);
        $randomIndex = rand(0, $prepareAccountCount-1);
        for( $i=0; $i<$prepareAccountCount; $i++ ) {
            $tmpAccount = new AccountModel();
            $tmpAccount->account = $startAccount + $i;
            $tmpAccount->status = AccountModel::ST_NOT_USED;
            $tmpAccount->save();
            if ( $randomIndex === $i ) {
                $account = $tmpAccount;
            } else {
                $tmpAccount = null;
                unset($tmpAccount);
            }
        }
        
        if ( $isFirstTime ) {
            $topRule = AccountModel::RL_MANAGEMENT_ACCOUNT | AccountModel::RL_EDITOR_ACCOUNT;
            $topRule = $topRule | AccountModel::RL_NORMAL_ACCOUNT;
            $account->role = $topRule;
            $account->save();
        }
        return $account;
    }
    
    /**
     * @var RegionManager
     */
    private $regionManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\RegionManager
     */
    public function getRegionManager() {
        if ( null === $this->regionManager ) {
            $this->regionManager = new RegionManager();
        }
        return $this->regionManager;
    }
    
    /**
     * @param string $attribute
     * @return string
     */
    public function getFindAttributeName( $attribute ) {
        return AccountModel::model()->getAttributeQueryName($attribute);
    }
}
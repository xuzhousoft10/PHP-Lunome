<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountModel;
use X\Module\Account\Service\Account\Core\Manager\ProfileManager;
use X\Module\Account\Service\Account\Core\Model\AccountProfileModel;
use X\Module\Account\Service\Account\Core\Manager\ConfigurationManager;
use X\Module\Account\Service\Account\Core\Manager\NotificationManager;
use X\Module\Account\Service\Account\Core\Manager\FriendManager;
use X\Module\Account\Service\Account\Core\Model\AccountOauth20Model;
/**
 * 
 */
class Account {
    /**
     * @var string
     */
    private $accountID = null;
    
    /**
     * @var AccountModel
     */
    private $accountModel = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Model\AccountModel
     */
    private function getAccountModel() {
        if ( null === $this->accountModel ) {
            $this->accountModel = AccountModel::model()->findByPrimaryKey($this->accountID);
        }
        return $this->accountModel;
    }
    
    /**
     * @return string
     */
    public function getID() {
        return $this->accountID;
    }
    
    /**
     * @param AccountModel|string $account
     */
    public function __construct( $account ) {
        if ( $account instanceof AccountModel ) {
            $this->accountModel = $account;
            $this->accountID = $account->id;
        } else {
            $this->accountID = $account;
        }
    }
    
    /**
     * 
     */
    public function login() {
        $account = $this->getAccountModel();
        
        $userInfo = array();
        $userInfo['isLogined']  = true;
        $userInfo['id']         = $this->accountID;
        $userInfo['account']    = (int)$account->account;
        $_SESSION['lunome']['user'] = $userInfo;
    }
    
    /**
     * 
     */
    public function logout(){
        $_SESSION['lunome'] = null;
        unset($_SESSION['lunome']);
        session_destroy();
        setcookie(session_name(),'',time()-3600);
    }
    
    /**
     * @var ProfileManager
     */
    private $profileManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ProfileManager
     */
    public function getProfileManager(){
        if ( null !== $this->profileManager ) {
            return $this->profileManager;
        }
        
        $profile = AccountProfileModel::model()->find(array('account_id'=>$this->accountID));
        if ( null === $profile ) {
            $account = $this->getAccountModel();
            $profile = new AccountProfileModel();
            $profile->account_id = $this->accountID;
            $profile->account_number = $account->account;
            $profile->save();
        }
        $this->profileManager = new ProfileManager($profile);
        return $this->profileManager;
    }
    
    /**
     * @var ConfigurationManager
     */
    private $configurationManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ConfigurationManager
     */
    public function getConfigurationManager(){
        if ( null === $this->configurationManager ) {
            $this->configurationManager = new ConfigurationManager($this->accountID);
        }
        
        return $this->configurationManager;
    }
    
    /**
     * @var NotificationManager
     */
    private $notificationManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\NotificationManager
     */
    public function getNotificationManager(){
        if ( null === $this->notificationManager ) {
            $this->notificationManager = new NotificationManager($this->accountID);
        }
        return $this->notificationManager;
    }
    
    /**
     * @var FriendManager
     */
    private $friendManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\FriendManager
     */
    public function getFriendManager(){
        if ( null === $this->friendManager ) {
            $this->friendManager = new FriendManager($this);
        }
        return $this->friendManager;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\OAuthInformation
     */
    public function getOAuthInformation() {
        $oauth = AccountOauth20Model::model()->find(array('account_id'=>$this->accountID));
        if ( null === $oauth ) {
            return null;
        }
        return new OAuthInformation($oauth);
    }
    
    public function getRoleManager(){}
    public function getActionHistoryManager(){}
    
}
<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountModel;
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
        if ( null === $this->accountModel ) {
            $this->accountModel = AccountModel::model()->findByPrimaryKey($this->accountID);
        }
        
        $userInfo = array();
        $userInfo['isLogined']  = true;
        $userInfo['id']         = $this->accountID;
        $userInfo['account']    = (int)$this->accountModel->account;
        $_SESSION['lunome']['user'] = $userInfo;
    }
    
    public function logout(){}
    
    public function getRoleManager(){}
    public function getNotificationManager(){}
    public function getActionHistoryManager(){}
    public function getConfigurationManager(){}
    public function getInformationManager(){}
    public function getFriendManager(){
        
    }
}
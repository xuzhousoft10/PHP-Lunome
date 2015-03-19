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
use X\Service\XRequest\Service as XRequestService;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var string
     */
    protected static $serviceName = 'User';
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        
        if ( $this->getIsDebugEnvironment() && $this->getIsGuest()) {
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
     * @return boolean
     */
    private function getIsDebugEnvironment() {
        /* @var $requestService XRequestService */
        $requestService = X::system()->getServiceManager()->get(XRequestService::getServiceName());
        
        $request = $requestService->getRequest();
        return $request->getHost() === X::system()->getConfiguration()->get('development_host');
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
     * @param unknown $roleCode
     * @return boolean
     */
    public function isEditor( $roleCode ) {
        $roleCode = (int)$roleCode;
        return 0 !== ($roleCode & AccountModel::RL_EDITOR_ACCOUNT);
    }
    
    /**
     * @param unknown $roleCode
     * @return boolean
     */
    public function isManager( $roleCode ) {
        $roleCode = (int)$roleCode;
        return 0 !== ($roleCode & AccountModel::RL_MANAGEMENT_ACCOUNT);
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
    
    public function getNotification( $id ) {
        $notification = AccountNotificationModel::model()->findByPrimaryKey($id);
        $notification = $notification->toArray();
        $sourceModel = $notification['source_model'];
        $sourceModel = $sourceModel::model()->findByPrimaryKey($notification['source_id']);
        $notification['sourceData'] = $sourceModel->toArray();
        return $notification;
    }
    
    /**
     * @param unknown $id
     * @return boolean
     */
    public function hasNotification( $id ) {
        return AccountNotificationModel::model()->exists(array('id'=>$id));
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
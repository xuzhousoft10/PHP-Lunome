<?php
namespace X\Module\Account\Service\Account\Core\Manager;
use X\Module\Account\Service\Account\Core\Model\AccountNotificationModel;
use X\Module\Account\Service\Account\Core\Instance\Notification;
/**
 * 
 */
class NotificationManager {
    /**
     * @var string
     */
    private $accountID = null;
    
    /**
     * @param string $accountID
     */
    public function __construct( $accountID ) {
        $this->accountID = $accountID;
    }
    
    /**
     * @return number
     */
    public function count(){
       $condition = array('status'=>AccountNotificationModel::STATUS_NEW, 'recipient_id'=>$this->accountID); 
       $count = AccountNotificationModel::model()->count($condition);
       return $count;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification[]
     */
    public function find() {
        $condition = array();
        $condition['status']        = AccountNotificationModel::STATUS_NEW;
        $condition['recipient_id']  = $this->accountID;
        $notifications = AccountNotificationModel::model($condition)->findAll($condition);
        foreach ( $notifications as $index => $notification ) {
            $notifications[$index] = new Notification($notification);
        }
        return $notifications;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function get( $id ) {
        $notification = AccountNotificationModel::model()->findByPrimaryKey($id);
        if ( null === $notification ) {
            return null;
        }
        return new Notification($notification);
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function create(){
        $notification = new AccountNotificationModel();
        $notification->produced_by = $this->accountID;
        $notification->created_at = date('Y-m-d H:i:s');
        return new Notification($notification);
    }
    
}
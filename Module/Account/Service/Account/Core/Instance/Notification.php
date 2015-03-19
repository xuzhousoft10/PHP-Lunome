<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountNotificationModel;
/**
 * 
 */
class Notification {
    /**
     * @var AccountNotificationModel
     */
    private $notificationModel = null;
    
    /**
     * @var array
     */
    private $sourceData = null;
    
    /**
     * @param AccountNotificationModel $notification
     */
    public function __construct( $notification ) {
        $this->notificationModel = $notification;
    }
    
    /**
     * @return array
     */
    public function getData() {
        if ( null === $this->sourceData ) {
            $sourceModel = $this->notificationModel->source_model;
            $sourceModel = $sourceModel::model()->findByPrimaryKey($this->notificationModel->source_id);
            $this->sourceData = $sourceModel->toArray();
        }
        return $this->sourceData;
    }
    
    /**
     * @return string
     */
    public function getView() {
        return $this->notificationModel->view;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function close() {
        $this->notificationModel->status = AccountNotificationModel::STATUS_CLOSED;
        $this->notificationModel->save();
        return $this;
    }
}
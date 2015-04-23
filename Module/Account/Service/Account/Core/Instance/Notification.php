<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Account\Service\Account\Core\Model\AccountNotificationModel;
use X\Module\Account\Service\Account\Core\Util\Exception;
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
     * @return string
     */
    public function getID() {
        return $this->notificationModel->id;
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
     * @param string $view
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function setView($view){
        $this->notificationModel->view = $view;
        return $this;
    }
    
    /**
     * @param mixed $model
     * @param string $primaryKeyAttributeName
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function setSourceDataModel($model, $primaryKeyAttributeName='id'){
        $this->notificationModel->source_id = $model->$primaryKeyAttributeName;
        $this->notificationModel->source_model = get_class($model);
        return $this;
    }
    
    /**
     * @param string $recipiendID
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function setRecipiendID($recipiendID) {
        $this->notificationModel->recipient_id = $recipiendID;
        return $this;
    }
    
    /**
     * @throws Exception
     * @return \X\Module\Account\Service\Account\Core\Instance\Notification
     */
    public function send() {
        if ( !$this->notificationModel->getIsNew() ) {
            throw new Exception('Unable to resend the notification.');
        }
        $this->notificationModel->save();
        return $this;
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
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getProducer() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $requester = $accountService->get($this->notificationModel->produced_by);
        return $requester;
    }
}
<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipRequestModel;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class ToBeFriendRequest {
    /**
     * @var AccountFriendshipRequestModel
     */
    private $requestModel = null;
    
    /**
     * @param AccountFriendshipRequestModel $requestModel
     */
    public function __construct($requestModel) {
        $this->requestModel = $requestModel;
    }
    
    /**
     * @param string $message
     * @param string $view
     */
    public function agree( $message, $view ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $currentAccount = $accountService->getCurrentAccount();
        $currentAccount->getFriendManager()->add($this->requestModel->requester_id);
        
        $this->answerTheRequest($message, $view, self::ANSWER_AGREE);
    }
    
    /**
     * @param string $message
     * @param string $view
     */
    public function refuse( $message, $view ) {
        $this->answerTheRequest($message, $view, self::ANSWER_REFUSE);
    }
    
    /**
     * @param string $message
     * @param string $view
     */
    private function answerTheRequest( $message, $view, $answer ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $currentAccount = $accountService->getCurrentAccount();
        
        $this->requestModel->result_message = $message;
        $this->requestModel->is_agreed = $answer;
        $this->requestModel->answered_at = date('Y-m-d H:i:s');
        $this->requestModel->save();
        
        $currentAccount->getNotificationManager()->create()
            ->setRecipiendID($this->requestModel->requester_id)
            ->setSourceDataModel($this->requestModel)
            ->setView($view)
            ->send();
    }
    
    /**
     * @var integer
     */
    const ANSWER_AGREE = 1;
    
    /**
     * @var integer
     */
    const ANSWER_REFUSE = 0;
}
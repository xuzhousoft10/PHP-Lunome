<?php
namespace X\Module\Account\Service\Account\Core\Manager;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipModel;
use X\Module\Account\Service\Account\Core\Model\AccountChatContentModel;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Account\Service\Account\Core\Instance\ChatMessage;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * 
 */
class ChatManager {
    /**
     * @var AccountFriendshipModel
     */
    private $friendshipModel = null;
    
    /**
     * @param AccountFriendshipModel $friendshipModel
     */
    public function __construct( $friendshipModel ) {
        $this->friendshipModel = $friendshipModel;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ChatManager
     */
    public function start(){
        $this->friendshipModel->is_chatting = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_YES;
        $this->friendshipModel->is_unread_notification_sended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_NO;
        $this->friendshipModel->save();
        return $this;
    }
    
    /**
     * @param string $content
     * @param string $notificationViewPath
     * @return \X\Module\Account\Service\Account\Core\Instance\ChatMessage
     */
    public function send( $content, $notificationViewPath ){
        $message = new AccountChatContentModel();
        $message->content = $content;
        $message->reader_id = $this->friendshipModel->account_friend;
        $message->writer_id = $this->friendshipModel->account_me;
        $message->wrote_at = date('Y-m-d H:i:s');
        $message->status = self::CHAT_MESSAGE_UNREAD;
        $message->save();
        
        $isChattingWithThisFriend = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_YES === $this->friendshipModel->is_chatting*1;
        $isUnreadNotificationSended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES === $this->friendshipModel->is_unread_notification_sended*1;
        if ( !$isChattingWithThisFriend && !$isUnreadNotificationSended ) {
            /* @var $accountService AccountService */
            $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
            $currentAccount = $accountService->getCurrentAccount();
            $currentAccount->getNotificationManager()->create()
                ->setView($notificationViewPath)
                ->setRecipiendID($this->friendshipModel->account_friend)
                ->setSourceDataModel($message)
                ->send();
            
            $this->friendshipModel->is_unread_notification_sended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES;
            $this->friendshipModel->save();
        }
        return new ChatMessage($message);
    }
    
    /**
     * @return number
     */
    public function countUnread() {
        $condition = array(
            'reader_id'=>$this->friendshipModel->account_me, 
            'writer_id'=>$this->friendshipModel->account_friend, 
            'status'=>self::CHAT_MESSAGE_UNREAD
        );
        return AccountChatContentModel::model()->count($condition);
    }
    
    /**
     * @param integer $length
     * @return \X\Module\Account\Service\Account\Core\Instance\ChatMessage[]
     */
    public function read( $length=null ){
        $condition = array(
            'reader_id'=>$this->friendshipModel->account_me,
            'writer_id'=>$this->friendshipModel->account_friend,
            'status'=>self::CHAT_MESSAGE_UNREAD
        );
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->limit = (null===$length) ? 0 : $length;
        $messages = AccountChatContentModel::model()->findAll($criteria);
        
        $messageIDList = array();
        foreach ( $messages as $index => $message ) {
            $messageIDList[] = $message->id;
            $messages[$index] = new ChatMessage($message);
        }
        if ( !empty( $messageIDList ) ) {
            AccountChatContentModel::model()->updateAll(array('status'=>self::CHAT_MESSAGE_READ), array('id'=>$messageIDList));
        }
        return $messages;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ChatManager
     */
    public function stop(){
        $this->friendshipModel->is_chatting = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_NO;
        $this->friendshipModel->save();
        return $this;
    }
    
    public function getIsChatting(){}
    
    /**
     * @var integer
     */
    const CHAT_MESSAGE_UNREAD = 1;
    
    /**
     * @var integer
     */
    const CHAT_MESSAGE_READ = 2;
    
    /**
     * @var integer
     */
    const ACCOUNT_IS_CHATTING_WITH_FRIEND_YES = 1;
    
    /**
     * @var integer
     */
    const ACCOUNT_IS_CHATTING_WITH_FRIEND_NO  = 0;
    
    /**
     * @var integer
     */
    const CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES = 1;
    
    /**
     * @var integer
     */
    const CHAT_IS_UNREAD_NOTIFICATION_SENDED_NO = 0;
}
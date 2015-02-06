<?php
/**
 * 
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Model\Account\AccountModel;
use X\Module\Lunome\Model\Account\AccountOauth20Model;
use X\Module\Lunome\Model\Account\AccountLoginHistoryModel;
use X\Module\Lunome\Model\Account\AccountHistoryModel;
use X\Module\Lunome\Model\Account\AccountConfigurationModel;
use X\Module\Lunome\Model\Account\AccountInformationModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Lunome\Model\Account\AccountFriendshipRequestModel;
use X\Module\Lunome\Model\Account\AccountFriendshipModel;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\Account\AccountChatContentModel;

/**
 * Handle all friend operations.
 */
class Account {
    /**
     * @param unknown $accountId
     * @param unknown $role
     */
    public function addRole( $accountId, $role ) {
        $role = (int)$role;
        $account = $this->get($accountId);
        $account->role = (int)$account->role | $role;
        $account->save();
    }
    
    /**
     * @param unknown $accountId
     * @param unknown $role
     */
    public function removeRole( $accountId, $role ) {
        $role = (int)$role;
        $account = $this->get($accountId);
        $account->role = (int)$account->role & ~$role;
        $account->save();
    }
    
    /**
     * @param unknown $id
     * @return AccountModel
     */
    public function get( $id ) {
        $account = AccountModel::model()->findByPrimaryKey($id);
        return $account;
    }
    
    /**
     * @param unknown $id
     * @return boolean
     */
    public function has( $id ) {
        return AccountModel::model()->exists(array('id'=>$id));
    }
    
    /**
     * @return array
     */
    public function findAll( $condition=null, $position=0, $limit=0 ) {
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $limit;
        $criteria->addOrder('account', 'ASC');
        $accounts = AccountModel::model()->findAll($criteria);
        foreach ( $accounts as $index => $account ) {
            $accounts[$index] = $account->toArray();
        }
        return $accounts;
    }
    
    /**
     * 
     * @param string $condition
     * @return number
     */
    public function count($condition=null) {
        return AccountModel::model()->count($condition);
    }
    
    /**
     * @param unknown $id
     * @return \X\Module\Lunome\Model\Oauth20Model 
     */
    public function getOauth( $id=null ) {
        if ( null === $id ) {
            /* @var $account AccountModel */
            $account = AccountModel::model()->findByPrimaryKey($this->getCurrentUserId());
            $id = $account->oauth20_id;
        }
        $oauth = AccountOauth20Model::model()->findByPrimaryKey($id);
        return (null === $oauth) ? null : $oauth->toArray();
    }
    
    /**
     * @param unknown $id
     */
    public function freeze( $id ) {
        /* @var $account AccountModel */
        $account = AccountModel::model()->findByPrimaryKey($id);
        $account->status = AccountModel::ST_FREEZE;
        $account->save();
        $this->logAction('ACCOUNT_FREEZE', $id);
    }
    
    /**
     * @param unknown $accountId
     * @param unknown $status
     */
    public function updateStatus($accountId, $status) {
        $account = $this->get($accountId);
        $account->status = $status;
        $account->save();
    }
    
    /**
     * @param unknown $id
     */
    public function unfreeze( $id ) {
        /* @var $account AccountModel */
        $account = AccountModel::model()->findByPrimaryKey($id);
        $account->status = AccountModel::ST_IN_USE;
        $account->save();
        $this->logAction('ACCOUNT_UNFREEZE', $id);
    }
    
    /**
     * 
     * @param unknown $id
     * @param number $position
     * @param number $limit
     * @return array
     */
    public function getLoginHistory( $id, $position=0, $limit=0 ) {
        $condition = array('account_id'=>$id);
        $orders = array('time'=>'DESC');
        $history = AccountLoginHistoryModel::model()->findAll($condition, $limit, $position, $orders);
        foreach ( $history as $index => $historyRecord ) {
            $history[$index] = $historyRecord->toArray();
        }
        return $history;
    }
    
    /**
     * 
     * @param unknown $id
     * @return number
     */
    public function getLoginHistoryCount( $id ) {
        return AccountLoginHistoryModel::model()->count(array('account_id'=>$id));
    }
    
    /**
     * 
     * @param unknown $action
     * @param string $target
     * @param string $code
     * @param string $message
     * @param string $comment
     * @param string $account
     */
    public function logAction( $action, $target=null, $code=null, $message=null, $comment=null, $account=null ) {
        if ( null === $account ) {
            /* @var $service \X\Module\Lunome\Service\User\Service */
            $service = X::system()->getServiceManager()->get(Service::getServiceName());
            $user = $service->getCurrentUser();
            $account = $user['ID'];
        }
        
        $history = new AccountHistoryModel();
        $history->account_id = $account;
        $history->time = date('Y-m-d H:i:s', time());
        $history->action = $action;
        $history->target = $target;
        $history->code = $code;
        $history->message = $message;
        $history->comment = $comment;
        $history->save();
    }
    
    /**
     * @param unknown $account
     */
    public function getConfigurationsByAccountId( $account ) {
        $condition = array(
            'account_id' => $account,
        );
        $configurations = AccountConfigurationModel::model()->findAll($condition);
        return $configurations;
    }
    
    /**
     * @var array
     */
    private $configurations = array();
    
    /**
     * 
     * @param unknown $type
     * @return AccountConfigurationModel[]
     */
    public function getConfigurations( $type ) {
        if (!isset($this->configurations[$type]) ) {
            $this->configurations[$type] = array();
            $configurations = AccountConfigurationModel::model()->findAll(array('type'=>$type));
            foreach ( $configurations as $configuration ) {
                /* @var $configuration AccountConfigurationModel */
                $this->configurations[$type][$configuration->name] = $configuration->value;
            }
        }
        return $this->configurations[$type];
    }
    
    /**
     * @param unknown $type
     * @param unknown $name
     * @param string $default
     */
    public function getConfiguration( $type, $name, $default=null ) {
        $configurations = $this->getConfigurations($type);
        if ( isset($configurations[$name]) ) {
            return $configurations[$name];
        } else {
            return $default;
        }
    }
    
    /**
     * @param unknown $type
     * @param unknown $values
     */
    public function setConfigurations( $type, $values ) {
        $currentUserId = $this->getCurrentUserId();
        AccountConfigurationModel::model()->deleteAll(array('type'=>$type, 'account_id'=>$currentUserId));
        $this->configurations[$type] = $values;
        foreach ( $values as $name => $value ) {
            $configuration = new AccountConfigurationModel();
            $configuration->account_id = $currentUserId;
            $configuration->type = $type;
            $configuration->name = $name;
            $configuration->value = $value;
            $configuration->save();
        }
    }
    
    /**
     * @param string $accountID
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel
     */
    public function getInformation( $accountID=null ) {
        $accountID = ( null === $accountID ) ? $this->getCurrentUserId() : $accountID;
        $info = AccountInformationModel::model()->find(array('account_id'=>$accountID));
        return $info;
    }
    
    /**
     * @param unknown $accountIDs
     * @return Ambigous <\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord, NULL>
     */
    public function getInformations ( $accountIDs ) {
        if ( empty($accountIDs) || !is_array($accountIDs) ) {
            return array();
        }
        
        $informations = AccountInformationModel::model()->findAll(array('account_id'=>$accountIDs));
        return $informations;
    }
    
    /**
     * @param unknown $information
     * @param string $accountID
     * @return \X\Module\Lunome\Model\Account\AccountInformation
     */
    public function updateInformation( $information, $accountID=null ) {
        $accountID = ( null === $accountID ) ? $this->getCurrentUserId() : $accountID;
        $account = $this->get($accountID);
        
        $informationModel = AccountInformationModel::model()->find(array('account_id'=>$accountID));
        if ( null === $informationModel ) {
            $informationModel = new AccountInformationModel();
        }
        $information['account_number'] = $account->account;
        $information['account_id'] = $accountID;
        $informationModel->setAttributeValues($information);
        $informationModel->save();
        return $informationModel;
    }
    
    /**
     * @param unknown $condition
     * @param unknown $offset
     * @param unknown $limit
     */
    public function findFriends( $condition, $offset=0, $limit=0 ) {
        $conditionObject = ConditionBuilder::build();
        if ( isset($condition['main']) ) {
            $conditionObject->groupStart()
                ->is('cellphone', $condition['main'])
                ->orThat()
                ->is('qq', $condition['main'])
                ->orThat()
                ->is('email', $condition['main'])
                ->orThat()
                ->is('account_number', $condition['main'])
            ->groupEnd();
            unset($condition['main']);
        }
        $conditionObject->addCondition($condition);
        
        /* Remove self information while search friends. */
        $conditionObject->isNot('account_id', $this->getCurrentUserId());
        
        /* Remove friends which already been. */
        $extCondition = array();
        $extCondition['account_friend'] = new SQLExpression(AccountInformationModel::model()->getTableFullName().'.account_id');
        $extCondition['account_me'] = $this->getCurrentUserId();
        $extCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($extCondition);
        $conditionObject->notExists($extCondition);
        
        $criteria = new Criteria();
        $criteria->position = $offset;
        $criteria->limit = $limit;
        $criteria->condition = $conditionObject;
        $informations = AccountInformationModel::model()->findAll($criteria);
        $count = AccountInformationModel::model()->count($conditionObject);
        return array('count'=>$count, 'data'=>$informations);
    }
    
    /**
     * @param unknown $friendAccountId
     * @return boolean
     */
    public function hasFriend( $friendAccountId ) {
        $condition = array();
        $condition['account_friend'] = $friendAccountId;
        $condition['account_me'] = $this->getCurrentUserId();
        return AccountFriendshipModel::model()->exists($condition);
    }
    
    public function countFriends() {
        $condition = array();
        $condition['account_me'] = $this->getCurrentUserId();
        return AccountFriendshipModel::model()->count($condition);
    }
    
    
    
    /**
     * @param unknown $recipient
     * @param unknown $message
     */
    public function sendToBeFriendRequest( $recipient, $message, $view ) {
        $request = new AccountFriendshipRequestModel();
        $request->message = $message;
        $request->recipient_id = $recipient;
        $request->request_started_at = date('Y-m-d H:i:s', time());
        $request->requester_id = $this->getCurrentUserId();
        $request->validate();
        $request->save();
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Account\\AccountFriendshipRequestModel';
        $sourceId       = $request->id;
        $recipiendId    = $recipient;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $recipiendId);
    }
    
    /**
     * @param unknown $requestID
     * @param unknown $isAgreed
     * @param unknown $message
     */
    public function updateToBeFriendRequestAnswer( $requestID, $isAgreed, $message, $view ) {
        /* @var $request AccountFriendshipRequestModel */
        $request = AccountFriendshipRequestModel::model()->findByPrimaryKey($requestID);
        $request->is_agreed         = $isAgreed ? 1 : 0;
        $request->result_message    = $message;
        $request->answered_at       = date('Y-m-d H:i:s');
        $request->save();
        
        if ( $isAgreed ) {
            $friendship = new AccountFriendshipModel();
            $friendship->started_at     = date('Y-m-d H:i:s');
            $friendship->account_me     = $request->recipient_id;
            $friendship->account_friend = $request->requester_id;
            $friendship->save();
            
            $friendship = new AccountFriendshipModel();
            $friendship->started_at     = date('Y-m-d H:i:s');
            $friendship->account_me     = $request->requester_id;
            $friendship->account_friend = $request->recipient_id;
            $friendship->save();
        } 
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Account\\AccountFriendshipRequestModel';
        $sourceId       = $request->id;
        $requster       = $request->requester_id;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $requster);
    }
    
    /**
     * @param string $requestId
     */
    public function hasToBeFriendRequest( $requestId ) {
        return AccountFriendshipRequestModel::model()->exists(array('id'=>$requestId));
    }
    
    /**
     * @param unknown $position
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel[]
     */
    public function getFriends( $position=0, $length=0 ) {
        $criteria = new Criteria();
        $criteria->condition = array('account_me'=>$this->getCurrentUserId());
        $criteria->position = $position;
        $criteria->limit = $length;
        $friends = AccountFriendshipModel::model()->findAll($criteria);
        if ( empty($friends) ) {
            return array();
        }
        foreach ( $friends as $index => $friend ) {
            $friends[$index] = $friend->account_friend;
        }
        $friends = AccountInformationModel::model()->findAll(array('account_id'=>$friends));
        return $friends;
    }
    
    /**
     * @param unknown $accountID
     * @param unknown $content
     */
    public function sendChatMessage( $accountID, $content, $notificationView ) {
        $currentUserID = $this->getCurrentUserId();
        $record = new AccountChatContentModel();
        $record->content = $content;
        $record->reader_id = $accountID;
        $record->writer_id = $currentUserID;
        $record->wrote_at = date('Y-m-d H:i:s');
        $record->status = self::CHAT_MESSAGE_UNREAD;
        $record->save();
        
        $condition = array();
        $condition['account_me'] = $accountID;
        $condition['account_friend'] = $currentUserID;
        /* @var $friendShipModel \X\Module\Lunome\Model\Account\AccountFriendshipModel */
        $friendShipModel = AccountFriendshipModel::model()->find($condition);
        
        $isChattingWithThisFriend = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_YES === $friendShipModel->is_chatting*1;
        $isUnreadNotificationSended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES === $friendShipModel->is_unread_notification_sended*1;
        if ( !$isChattingWithThisFriend && !$isUnreadNotificationSended ) {
            $sourceModel = 'X\\Module\\Lunome\\Model\\Account\\AccountChatContentModel';
            $sourceId = $record->id;
            $this->getUserService()->sendNotification($notificationView, $sourceModel, $sourceId, $accountID);
            $friendShipModel->is_unread_notification_sended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES;
            $friendShipModel->save();
        }
        
        return $record;
    }
    
    /**
     * @param unknown $friendID
     */
    public function cleanIsUnreadMotificationSendedMark( $friendID ) {
        $condition = array();
        $condition['account_me'] = $this->getCurrentUserId();
        $condition['account_friend'] = $friendID;
        /* @var $friendShipModel \X\Module\Lunome\Model\Account\AccountFriendshipModel */
        $friendShipModel = AccountFriendshipModel::model()->find($condition);
        $friendShipModel->is_unread_notification_sended = self::CHAT_IS_UNREAD_NOTIFICATION_SENDED_NO;
        $friendShipModel->save();
    }
    
    const CHAT_IS_UNREAD_NOTIFICATION_SENDED_YES = 1;
    const CHAT_IS_UNREAD_NOTIFICATION_SENDED_NO = 0;
    
    /**
     * @param string $markAsRead
     */
    public function getUnreadChatMessages( $writerId, $markAsRead=true ) {
        $condition = array();
        $condition['writer_id'] = $writerId;
        $condition['status'] = self::CHAT_MESSAGE_UNREAD;
        $condition['reader_id'] = $this->getCurrentUserId();
        $messages = AccountChatContentModel::model()->findAll($condition);
        foreach ( $messages as $message ) {
            $message->status = self::CHAT_MESSAGE_READ;
            $message->save();
        }
        return $messages;
    }
    
    /**
     * @param unknown $friendId
     */
    public function countUnreadChatMessagesByFriendID( $friendId ) {
        $condition = array();
        $condition['writer_id'] = $friendId;
        $condition['status'] = self::CHAT_MESSAGE_UNREAD;
        $condition['reader_id'] = $this->getCurrentUserId();
        $count = AccountChatContentModel::model()->count($condition);
        return $count;
    }
    
    /**
     * 标记当前用户正在与指定好友聊天。
     * @param string $friendID 好友ID
     */
    public function markChattingWithFriend( $friendID ) {
        $condition = array();
        $condition['account_me'] = $this->getCurrentUserId();
        $condition['account_friend'] = $friendID;
        $friendShip = AccountFriendshipModel::model()->find($condition);
        if ( null !== $friendShip ) {
            $friendShip->is_chatting = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_YES;
            $friendShip->save();
        }
    }
    
    /**
     * 标记当前用户结束与指定好友聊天。
     * @param string $friendID 好友ID
     */
    public function unmarkChattingWithFriend($friendID) {
        $condition = array();
        $condition['account_me'] = $this->getCurrentUserId();
        $condition['account_friend'] = $friendID;
        $friendShip = AccountFriendshipModel::model()->find($condition);
        if ( null !== $friendShip ) {
            $friendShip->is_chatting = self::ACCOUNT_IS_CHATTING_WITH_FRIEND_NO;
            $friendShip->save();
        }
    }
    
    /**
     * 标记当前用户结束与指定好友聊天。
     * @param string $friendID 好友ID
     */
    public function getIsChattingWithFriend( $friendID, $myId=null ) {
        $condition = array();
        $condition['account_me'] = (null===$myId) ? $this->getCurrentUserId() : $myId;
        $condition['account_friend'] = $friendID;
        $friendShip = AccountFriendshipModel::model()->find($condition);
        if ( null !== $friendShip ) {
            return $friendShip->is_chatting*1 === self::ACCOUNT_IS_CHATTING_WITH_FRIEND_YES;
        } else {
            return false;
        }
    }
    
    const ACCOUNT_IS_CHATTING_WITH_FRIEND_YES = 1;
    const ACCOUNT_IS_CHATTING_WITH_FRIEND_NO  = 0;
    
    const CHAT_MESSAGE_UNREAD = 1;
    const CHAT_MESSAGE_READ = 2;
    
    private $userService = null;
    
    /**
     * @return \X\Module\Lunome\Service\User\Service
     */
    private function getUserService() {
        if ( null === $this->userService ) {
            $this->userService = X::system()->getServiceManager()->get(Service::getServiceName());
        }
        return $this->userService;
    }
    
    public function getSexNames() {
        return array(0=>'保密',1=>'男',2=>'女',3=>'其他');
    }
    
    public function getSexName( $sex ) {
        $sex = intval($sex);
        $codes = $this->getSexNames();
        return isset($codes[$sex]) ? $codes[0] : $codes[$sex];
    }
    
    public function getSexMarks() {
        return array(0=>'＊',1=>'♂',2=>'♀',3=>'？');
    }
    
    public function getSexMark( $sex ) {
        $sex = intval($sex);
        $codes = $this->getSexMarks();
        return isset($codes[$sex]) ? $codes[0] : $codes[$sex];
    }
    
    public function getSexualityNames() {
        return array(0=>'保密',1=>'异性',2=>'同性',3=>'双性',4=>'无性',5=>'二禁');
    }
    
    public function getSexualityName( $code ) {
        $code = intval($code);
        $codes = $this->getSexualityNames();
        return isset($codes[$code]) ? $codes[0] : $codes[$code];
    }
    
    public function getEmotionStatuNames() {
        return array(0=>'保密',1=>'单身',2=>'热恋中',3=>'同居',4=>'已订婚',5=>'已婚',6=>'分居',7=>'离异',8=>'很难说',9=>'其他');
    }
    
    public function getEmotionStatuName( $code ) {
        $code = intval($code);
        $codes = $this->getEmotionStatuNames();
        return isset($codes[$code]) ? $codes[0] : $codes[$code];
    }
    
    /**
     * @return string
     */
    private function getCurrentUserId() {
        return $this->getUserService()->getCurrentUserId();
    }
    
    /* Account setting types */
    const SETTING_TYPE_SNS = 'sns';
    
    /* Actions */
    const ACTION_ENABLE = 'ACCOUNT_ENABLE';
    const ACTION_LOGIN  = 'ACCOUNT_LOGIN';
    
    /* Result codes */
    const RESULT_CODE_SUCCESS = 0;
}
<?php
namespace X\Module\Account\Service\Account\Core\Manager;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipModel;
use X\Module\Account\Service\Account\Core\Model\AccountModel;
use X\Module\Account\Service\Account\Core\Instance\Account;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Util\Expression as SQLExpression;
use X\Module\Account\Service\Account\Core\Model\AccountProfileModel;
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipRequestModel;
use X\Module\Account\Service\Account\Core\Instance\ToBeFriendRequest;
use X\Module\Account\Service\Account\Core\Instance\Friend;
/**
 * 
 */
class FriendManager {
    /**
     * @var string
     */
    private $accountID = null;
    
    /**
     * @var Account
     */
    private $account = null;
    
    /**
     * @param Account $account
     */
    public function __construct( $account ) {
        $this->account = $account;
        $this->accountID = $account->getID();
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Account\Service\Account\Core\Instance\Account[]
     */
    public function find( $condition=null ){
        $condition = $this->initTheConditionForFindMyFriends($condition);
        $friendShips = AccountFriendshipModel::model()->findAll($condition);
        if ( empty($friendShips) ) {
            return array();
        }
        
        foreach ( $friendShips as $index => $friendShip ) {
            $friendShips[$index] = $friendShip->account_friend;
        }
        
        $friends = AccountModel::model()->findAll(array('id'=>$friendShips));
        foreach ( $friends as $index => $friend ) {
            $friends[$index] = new Account($friend);
        }
        return $friends;
    }
    
    /**
     * @param unknown $condition
     * @return number
     */
    public function count( $condition=null ) {
        $condition = $this->initTheConditionForFindMyFriends($condition);
        return AccountFriendshipModel::model()->count($condition->condition);
    }
    
    /**
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\ActiveRecord\Criteria
     */
    private function initTheConditionForFindMyFriends( $condition ) {
        if ( !($condition instanceof Criteria) ) {
            $condition = ConditionBuilder::build($condition);
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        if ( !($condition->condition instanceof ConditionBuilder ) ) {
            $condition->condition = ConditionBuilder::build($condition->condition);
        }
        
        $condition->condition->andAlso()->is('account_me', $this->accountID);
        return $condition;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Account\Service\Account\Core\Instance\Account[]
     */
    public function findNonFriends($condition) {
        $condition = $this->initTheConditionForFindNonFriends($condition);
        $accounts = array();
        $profiles = AccountProfileModel::model()->findAll($condition);
        foreach ( $profiles as $index => $profile ) {
            $accounts[$index] = new Account($profile->account_id);
        }
        return $accounts;
    }
    
    /**
     * @param mixed $condition
     * @return number
     */
    public function countNonFriends($condition) {
        $condition = $this->initTheConditionForFindNonFriends($condition);
        $condition = $condition->condition;
        return AccountProfileModel::model()->count($condition);
    }
    
    /**
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\ActiveRecord\Criteria
     */
    private function initTheConditionForFindNonFriends( $condition ) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        if ( !($condition->condition instanceof ConditionBuilder ) ) {
            $conditionObject = ConditionBuilder::build();
            if ( is_array($condition->condition) ) {
                if ( isset($condition->condition['main']) ) {
                    $conditionObject->groupStart()
                    ->includes('nickname', $condition->condition['main'])
                    ->orThat()
                    ->is('cellphone', $condition->condition['main'])
                    ->orThat()
                    ->is('qq', $condition->condition['main'])
                    ->orThat()
                    ->is('email', $condition->condition['main'])
                    ->orThat()
                    ->is('account_number', $condition->condition['main'])
                    ->groupEnd();
                    unset($condition->condition['main']);
                }
                $conditionObject->addCondition($condition->condition);
            }
            $condition->condition = $conditionObject;
        }
        
        /* Remove self information while search friends. */
        $condition->condition->isNot('account_id', $this->accountID);
        
        /* Remove friends which already been. */
        $extCondition = array();
        $extCondition['account_friend'] = new SQLExpression(AccountProfileModel::model()->getTableFullName().'.account_id');
        $extCondition['account_me'] = $this->accountID;
        $extCondition = AccountFriendshipModel::query()->addExpression('id')->find($extCondition);
        $condition->condition->notExists($extCondition);
        return $condition;
    }
    
    /**
     * @param string $accountID
     * @return boolean
     */
    public function isFriendWith( $accountID ) {
        $condition = array('account_me'=>$this->accountID, 'account_friend'=>$accountID);
        return AccountFriendshipModel::model()->exists($condition);
    }
    
    /**
     * @param string $recipient
     * @param string $message
     * @param string $view
     */
    public function sendToBeFriendRequest( $recipient, $message, $view ) {
        $request = new AccountFriendshipRequestModel();
        $request->message = $message;
        $request->recipient_id = $recipient;
        $request->request_started_at = date('Y-m-d H:i:s', time());
        $request->requester_id = $this->accountID;
        $request->save();
        
        $this->account->getNotificationManager()->create()
            ->setView($view)
            ->setSourceDataModel($request)
            ->setRecipiendID($recipient)
            ->send();
    }
    
    /**
     * @param string $requestID
     * @return \X\Module\Account\Service\Account\Core\Instance\ToBeFriendRequest
     */
    public function getToBeFriendRequest( $requestID ) {
        $request = AccountFriendshipRequestModel::model()->findByPrimaryKey($requestID);
        if ( null === $request ) {
            return null;
        }
        return new ToBeFriendRequest($request);
    }
    
    /**
     * @param string $accountID
     */
    public function add( $accountID ) {
        $friendship = new AccountFriendshipModel();
        $friendship->account_me = $this->accountID;
        $friendship->account_friend = $accountID;
        $friendship->started_at = date('Y-m-d H:i:s');
        $friendship->save();
        
        $friendship = new AccountFriendshipModel();
        $friendship->account_me = $accountID;
        $friendship->account_friend = $this->accountID;
        $friendship->started_at = date('Y-m-d H:i:s');
        $friendship->save();
    }
    
    /**
     * @param string $accountID
     * @return \X\Module\Account\Service\Account\Core\Instance\Friend
     */
    public function get( $accountID ){
        $condition = array('account_me'=>$this->accountID, 'account_friend'=>$accountID);
        $friendShip = AccountFriendshipModel::model()->find($condition);
        if ( null === $friendShip ) {
            return null;
        }
        return new Friend($friendShip);
    }
    
    /**
     * @param string $attribute
     * @return string
     */
    public function getFindAttributeName( $attribute ) {
        return AccountFriendshipModel::model()->getAttributeQueryName($attribute);
    }
    
    public function getGroupManager(){}
}
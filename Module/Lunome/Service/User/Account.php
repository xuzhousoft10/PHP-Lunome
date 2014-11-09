<?php
/**
 * 
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Model\AccountModel;
use X\Module\Lunome\Model\Oauth20Model;
use X\Module\Lunome\Model\AccountLoginHistoryModel;
use X\Module\Lunome\Model\AccountHistoryModel;

/**
 * Handle all friend operations.
 */
class Account {
    /**
     * @return array
     */
    public function findAll( $condition=null, $position=0, $limit=0 ) {
        $accounts = AccountModel::model()->findAll($condition, $limit, $position, array('account'=>'ASC'));
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
     */
    public function getOauth( $id ) {
        $oauth = Oauth20Model::model()->findByPrimaryKey($id);
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
     * @param unknown $id
     */
    public function addAdmin( $id ) {
        $account = AccountModel::model()->findByPrimaryKey($id);
        $account->is_admin = AccountModel::IS_ADMIN_YES;
        $account->save();
        $this->logAction('ACCOUNT_ADMIN_ADD', $id);
    }
    
    /**
     * 
     * @param unknown $id
     */
    public function deleteAdmin( $id ) {
        $account = AccountModel::model()->findByPrimaryKey($id);
        $account->is_admin = AccountModel::IS_ADMIN_NO;
        $account->save();
        $this->logAction('ACCOUNT_ADMIN_DELETE', $id);
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
        }
        
        $history = new AccountHistoryModel();
        $history->account_id = $user['ID'];
        $history->time = date('Y-m-d H:i:s', time());
        $history->action = $action;
        $history->target = $target;
        $history->code = $code;
        $history->message = $message;
        $history->comment = $comment;
        $history->save();
    }
    

    /* Actions */
    const ACTION_ENABLE = 'ACCOUNT_ENABLE';
    const ACTION_LOGIN  = 'ACCOUNT_LOGIN';
    
    /* Result codes */
    const RESULT_CODE_SUCCESS = 0;
}
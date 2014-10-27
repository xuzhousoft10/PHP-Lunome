<?php
/**
 * 
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Module\Lunome\Model\AccountModel;
use X\Module\Lunome\Model\Oauth20Model;
use X\Module\Lunome\Model\AccountLoginHistoryModel;

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
    }
    
    /**
     * @param unknown $id
     */
    public function unfreeze( $id ) {
        /* @var $account AccountModel */
        $account = AccountModel::model()->findByPrimaryKey($id);
        $account->status = AccountModel::ST_IN_USE;
        $account->save();
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
}
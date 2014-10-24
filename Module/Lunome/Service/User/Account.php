<?php
/**
 * 
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Module\Lunome\Model\AccountModel;

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
}
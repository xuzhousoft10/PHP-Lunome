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
    public function getAll() {
        $accounts = AccountModel::model()->findAll();
        foreach ( $accounts as $index => $account ) {
            $accounts[$index] = $account->toArray();
        }
        return $accounts;
    }
}
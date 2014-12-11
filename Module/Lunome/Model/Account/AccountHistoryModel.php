<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_id
 * @property string $time
 * @property string $action
 * @property string $target
 * @property string $code
 * @property string $message
 * @property string $comment
 **/
class AccountHistoryModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['account_id']  = 'VARCHAR(36) NOTNULL';
        $columns['time']        = 'DATETIME NOTNULL';
        $columns['action']      = 'VARCHAR(64) NOTNULL';
        $columns['target']      = 'VARCHAR(36) NOTNULL';
        $columns['code']        = 'TINYINT UNSIGNED [0]';
        $columns['message']     = 'VARCHAR(128)';
        $columns['comment']     = 'VARCHAR(128)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_history';
    }
}
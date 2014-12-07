<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_id
 * @property string $type
 * @property string $name
 * @property string $value
 **/
class AccountConfigurationModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NN';
        $columns['account_id']  = 'VARCHAR(36) NN';
        $columns['type']        = 'VARCHAR(32)';
        $columns['name']        = 'VARCHAR(64)';
        $columns['value']       = 'VARCHAR(64)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_configurations';
    }
}
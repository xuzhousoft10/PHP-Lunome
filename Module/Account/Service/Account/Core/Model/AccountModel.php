<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 *
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $account
 * @property string $oauth20_id
 * @property string $status
 * @property string $enabled_at
 * @property string $role
 **/
class AccountModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['account']     = 'INT NOTNULL';
        $columns['status']      = 'TINYINT NOTNULL';
        $columns['enabled_at']  = 'DATETIME';
        $columns['role']        = 'TINYINT [1] UNSIGNED';
        return $columns;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'accounts';
    }
    
    /**
     * @var integer
     */
    const RL_NORMAL_ACCOUNT     = 1;
    /**
     * @var integer
     */
    const RL_EDITOR_ACCOUNT     = 2;
    /**
     * @var integer
     */
    const RL_MANAGEMENT_ACCOUNT = 4;
    
    /**
     * @var integer
     */
    const ST_NOT_USED   = 1;
    /**
     * @var integer
     */
    const ST_IN_USE     = 2;
    /**
     * @var integer
     */
    const ST_FREEZE     = 3;
}
<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account
 * @property string $oauth20_id
 * @property string $status
 * @property string $enabled_at
 * @property string $is_admin
 * 
 * delimiter $$

CREATE TABLE `accounts` (
  `id` varchar(36) NOT NULL,
  `account` int(11) NOT NULL,
  `oauth20_id` varchar(36) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `enabled_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


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
        $columns['oauth20_id']  = 'VARCHAR(36)';
        $columns['status']      = 'TINYINT NOTNULL';
        $columns['enabled_at']  = 'DATETIME';
        $columns['is_admin']    = 'TINYINT [0] UNSIGNED';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'accounts';
    }
    
    const IS_ADMIN_YES = 1;
    const IS_ADMIN_NO  = 0;
    
    const ST_NOT_USED   = 1;
    const ST_IN_USE     = 2;
    const ST_FREEZE     = 3;
}
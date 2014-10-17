<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_me
 * @property string $account_him
 * @property string $started_at
 * 
 * delimiter $$

CREATE TABLE `account_friendships` (
  `id` varchar(36) NOT NULL,
  `account_me` varchar(36) NOT NULL,
  `account_him` varchar(36) NOT NULL,
  `started_at` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


 **/
class AccountFriendshipModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('account_me')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('account_him')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('started_at')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_friendships';
    }
}
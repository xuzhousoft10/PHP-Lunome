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
 * @property string $account
 * @property string $oauth20_id
 * @property string $nickname
 * @property string $status
 * @property string $enabled_at
 * @property string $photo
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
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('account')->setType(ColumnType::T_INT)->setNullable(false);
        $columns[] = Column::create('oauth20_id')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $columns[] = Column::create('nickname')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('status')->setType(ColumnType::T_TINYINT)->setNullable(false);
        $columns[] = Column::create('enabled_at')->setType(ColumnType::T_DATETIME);
        $columns[] = Column::create('photo')->setType(ColumnType::T_VARCHAR)->setLength(256);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'accounts';
    }
    
    const ST_NOT_USED   = 1;
    const ST_IN_USE     = 2;
    const ST_FREEZE     = 3;
}
<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;
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
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('type')->setType(ColumnType::T_VARCHAR)->setLength(32);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('value')->setType(ColumnType::T_VARCHAR)->setLength(64);
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
<?php
namespace X\Module\Lunome\Model\Account\Profile;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $account_id
 * @property string $started_at
 * @property string $ended_at
 * @property string $positions_id
 * @property string $company_id
 **/
class WorkingHistoryModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('started_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('ended_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('positions_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_profile_working_history';
    }
}
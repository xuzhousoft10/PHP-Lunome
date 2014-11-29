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
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('time')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $columns[] = Column::create('action')->setType(ColumnType::T_VARCHAR)->setLength(64)->setNullable(false);
        $columns[] = Column::create('target')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $columns[] = Column::create('code')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true)->setDefault(0);
        $columns[] = Column::create('message')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('comment')->setType(ColumnType::T_VARCHAR)->setLength(128);
        
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
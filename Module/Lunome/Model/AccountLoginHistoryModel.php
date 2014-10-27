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
 * @property string $account_id
 * @property string $time
 * @property string $ip
 * @property string $logined_by
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $isp
 **/
class AccountLoginHistoryModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('time')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $columns[] = Column::create('ip')->setType(ColumnType::T_VARCHAR)->setLength(64)->setNullable(false);
        $columns[] = Column::create('logined_by')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        $columns[] = Column::create('country')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('province')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('city')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('isp')->setType(ColumnType::T_VARCHAR)->setLength(64);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_login_history';
    }
}
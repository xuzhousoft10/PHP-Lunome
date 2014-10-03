<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $registered_at
 * @property string $expired_at
 **/
class UnverifiedRegistrationModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('email')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('password')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('registered_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $columns[] = Column::create('expired_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_unverified_registration';
    }
}
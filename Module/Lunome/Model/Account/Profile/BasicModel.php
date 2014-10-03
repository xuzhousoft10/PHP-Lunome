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
 * @property string $nickname
 * @property string $photo
 * @property string $realname
 * @property string $sex
 * @property string $birthday
 * @property string $nationality
 * @property string $blood_type
 * @property string $sexual_orientation
 * @property string $religiou_id
 **/
class BasicModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('nickname')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        $columns[] = Column::create('photo')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('realname')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('sex')->setType(ColumnType::T_TINYINT);
        $columns[] = Column::create('birthday')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('nationality')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('blood_type')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('sexual_orientation')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('religiou_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_profile_basic';
    }
}
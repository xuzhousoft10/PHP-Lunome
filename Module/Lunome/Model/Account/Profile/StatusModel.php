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
 * @property string $relationship_status
 * @property string $in_a_relationship_with
 * @property string $education
 * @property string $is_working
 * @property string $work
 * @property string $emergency_contact
 * @property string $contact_place
 * @property string $school_id
 * @property string $company_id
 **/
class StatusModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('relationship_status')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('in_a_relationship_with')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('education')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('is_working')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('work')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('emergency_contact')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('contact_place')->setType(ColumnType::T_VARCHAR)->setLength(45);
        $columns[] = Column::create('school_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_profile_status';
    }
}
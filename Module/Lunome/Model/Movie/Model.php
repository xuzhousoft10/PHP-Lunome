<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $name
 * @property string $region_id
 * @property string $length
 * @property string $overview
 * @property string $is_adult
 * @property string $language_id
 * @property string $released_at
 * @property string $directors_id
 * @property string $budget
 * @property string $color
 * @property string $production_company_id
 * @property string $box_office
 **/
class Model extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(256)->setNullable(false);
        $columns[] = Column::create('region_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('length')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('overview')->setType(ColumnType::T_VARCHAR)->setLength(1024);
        $columns[] = Column::create('is_adult')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('language_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('released_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('directors_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('budget')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('color')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('production_company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('box_office')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movies';
    }
}
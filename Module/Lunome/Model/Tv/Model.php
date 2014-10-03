<?php
namespace X\Module\Lunome\Model\Tv;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $name
 * @property string $production_company_id
 * @property string $region_id
 * @property string $episode_count
 * @property string $episode_lenght
 * @property string $season_count
 * @property string $language_id
 * @property string $color
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
        $columns[] = Column::create('production_company_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('region_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('episode_count')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('episode_lenght')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('season_count')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('language_id')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('color')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'tvs';
    }
}
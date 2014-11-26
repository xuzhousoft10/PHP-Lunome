<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $name
 * @property string $length
 * @property string $date
 * @property string $region_id
 * @property string $category
 * @property string $language_id
 * @property string $director
 * @property string $writer
 * @property string $producer
 * @property string $executive
 * @property string $actor
 * @property string $introduction
 * @property string $has_cover
 **/
class MovieModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('length')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('date')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('region_id')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $columns[] = Column::create('language_id')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $columns[] = Column::create('director')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('writer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('producer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('executive')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('actor')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(1024);
        $columns[] = Column::create('has_cover')->setType(ColumnType::T_TINYINT)->setDefault(0);
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
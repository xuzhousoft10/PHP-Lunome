<?php
namespace X\Module\Lunome\Model;

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
 * @property string $year
 * @property string $region
 * @property string $category
 * @property string $language
 * @property string $director
 * @property string $writer
 * @property string $producer
 * @property string $executive
 * @property string $actor
 * @property string $introduction
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
        $columns[] = Column::create('year')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('language')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('director')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('writer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('producer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('executive')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('actor')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(1024);
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
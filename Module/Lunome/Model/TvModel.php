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
 * @property string $name
 * @property string $episode_count
 * @property string $episode_length
 * @property string $season_count
 * @property string $premiered_at
 * @property string $region
 * @property string $language
 * @property string $category
 * @property string $director
 * @property string $writer
 * @property string $producer
 * @property string $executive
 * @property string $actor
 * @property string $introduction
 **/
class TvModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('episode_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('episode_length')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('season_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('premiered_at')->setType(ColumnType::T_DATE);
        $columns[] = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('language')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $columns[] = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
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
        return 'media_tvs';
    }
}
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
 * @property string $movie_id
 * @property string $category_id
 **/
class MovieCategoryMapModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('movie_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('category_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_category_maps';
    }
}
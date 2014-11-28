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
 * @property string $count
 * @property string $beg_message
 * @property string $recommend_message
 * @property string $share_message
 **/
class MovieCategoryModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(45);
        $columns[] = Column::create('count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('beg_message')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('recommend_message')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('share_message')->setType(ColumnType::T_VARCHAR)->setLength(256);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_categories';
    }
}
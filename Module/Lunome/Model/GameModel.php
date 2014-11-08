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
 * @property string $category
 * @property string $is_multi_player
 * @property string $screen_dimension
 * @property string $area
 * @property string $published_at
 * @property string $published_by
 * @property string $developed_by
 * @property string $introduction
 **/
class GameModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('is_multi_player')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('screen_dimension')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('area')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('published_at')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('published_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('developed_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(128);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_games';
    }
}
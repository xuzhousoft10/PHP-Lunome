<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;
use X\Module\Lunome\Util\Model\Poster;

/**
 * @property string $id
 * @property string $tv_id
 * @property string $data
 **/
class TvPosterModel extends Poster {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Model\Poster::getMediaKey()
     */
    public function getMediaKey() {
        return 'tv_id';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('tv_id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('data')->setType(ColumnType::T_LONGTEXT);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'tv_posters';
    }
}
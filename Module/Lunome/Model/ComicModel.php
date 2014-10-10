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
 **/
class ComicModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'comics';
    }
}
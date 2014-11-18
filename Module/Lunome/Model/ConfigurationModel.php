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
 * @property string $value
 * @property string $updated_at
 * @property string $updated_by
 **/
class ConfigurationModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false)->setIsPrimaryKey(true);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('value')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $columns[] = Column::create('updated_at')->setType(ColumnType::T_DATETIME);
        $columns[] = Column::create('updated_by')->setType(ColumnType::T_VARCHAR)->setLength(36);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'configurations';
    }
}
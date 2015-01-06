<?php
namespace X\Module\Lunome\Model\Region;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $parent
 * @property string $level
 **/
class RegionModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']        = 'VARCHAR(128) NOTNULL';
        $columns['parent']      = 'VARCHAR(36)';
        $columns['level']       = 'INT';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'regions';
    }
}
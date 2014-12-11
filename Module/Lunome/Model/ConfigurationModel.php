<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
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
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']        = 'VARCHAR(128) NOTNULL';
        $columns['value']       = 'VARCHAR(256)';
        $columns['updated_at']  = 'DATETIME';
        $columns['updated_by']  = 'VARCHAR(36)';
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
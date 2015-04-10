<?php
/**
 * 
 */
namespace X\Util\Model;
/**
 * @property string $id
 * @property string $host_id
 * @property string $content
 * @property string $record_created_at
 * @property string $record_created_by
 */
abstract class ShortComment extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['host_id']        = 'VARCHAR(36) NOTNULL';
        $columns['content']             = 'VARCHAR(1024)';
        $columns['record_created_at']   = 'DATETIME';
        $columns['record_created_by']   = 'VARCHAR(36)';
        return $columns;
    }
}
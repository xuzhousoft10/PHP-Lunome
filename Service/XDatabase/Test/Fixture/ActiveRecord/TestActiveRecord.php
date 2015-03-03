<?php
namespace X\Service\XDatabase\Test\Fixture\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Core\ActiveRecord\XActiveRecord;
/**
 * 
 */
class TestActiveRecord extends XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['value']       = 'VARCHAR(36)';
        $columns['mark']        = 'VARCHAR(36) UNIQUE NOTNULL';
        $columns['status']      = 'INT [1]';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'test_activerecord';
    }
}
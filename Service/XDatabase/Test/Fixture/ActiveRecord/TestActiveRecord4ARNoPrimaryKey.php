<?php
namespace X\Service\XDatabase\Test\Fixture\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Core\ActiveRecord\XActiveRecord;
/**
 * 
 */
class TestActiveRecord4ARNoPrimaryKey extends XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['value']       = 'VARCHAR(36)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'test_activerecord_4_ar_no_primary_key';
    }
}
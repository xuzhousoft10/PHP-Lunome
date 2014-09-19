<?php
/**
 * active.record.test.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
namespace X\Database\Test\Resources;

/**
 * Import namespaces.
 */
use \X\Database\ActiveRecord\ActiveRecord;

/**
 * ActiveRecordTest
 */
class ActiveRecordNoPkTester extends ActiveRecord {
    /**
     * The table name
     * 
     * @var string
     */
    const TABLE_NAME = 'active_record_no_pk_tester';
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::getTableName()
     */
    public function getTableName() {
        return self::TABLE_NAME;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::describe()
     */
    protected function describe() {
        $describe = array();
        $describe['column'] = 'INT(11)';
        return $describe;
    }
}

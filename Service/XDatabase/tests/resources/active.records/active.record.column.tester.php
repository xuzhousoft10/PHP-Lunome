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
 * ActiveRecordColumnTester
 */
class ActiveRecordColumnTester extends ActiveRecord {
    /**
     * (non-PHPdoc)
     * @see ActiveRecord::getTableName()
     */
    public function getTableName() {
        return 'test_table';
    }

    /**
     * (non-PHPdoc)
     * @see ActiveRecord::describe()
     */
    protected function describe() {
        return array();
    }
}

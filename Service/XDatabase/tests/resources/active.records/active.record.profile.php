<?php
/**
 * active.record.profile.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */
namespace X\Database\Test\Resources;
/**
 * 
 */
use X\Database\ActiveRecord\ActiveRecord;
/**
 * ActiveRecordProfile
 */
class ActiveRecordProfile extends ActiveRecord {
    /**
     * 
     * @var unknown
     */
    const TABLE_NAME = 'active_record_test_profiles';
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::getTableName()
     */
    public function getTableName() {
        return 'active_record_test_profiles';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id'] = 'PK AI INT';
        $columns['activerecordmember_id'] = 'INT NU';
        $columns['age'] = 'INT UN';
        return $columns;
    }
}
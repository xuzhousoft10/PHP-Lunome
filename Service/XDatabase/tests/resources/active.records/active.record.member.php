<?php
/**
 * active.record.member.php
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
 * ActiveRecordMember
 */
class ActiveRecordMember extends ActiveRecord {
    /**
     * 
     * @var unknown
     */
    const TABLE_NAME = 'active_record_test_members';
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::getTableName()
     */
    public function getTableName() {
        return 'active_record_test_members';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id'] = 'PK AI INT';
        $columns['activerecordgroup_id'] = 'INT "0"';
        return $columns;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::relationships()
     */
    protected function relationships() {
        $this->hasOne('ActiveRecordProfile');
        $this->hasMany('ActiveRecordChildren');
        $this->manyToMany('ActiveRecordMember');
        $this->belongsTo('ActiveRecordGroup');
    }
}
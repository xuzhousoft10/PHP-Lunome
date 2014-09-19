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
class ActiveRecordMemberActiveRecordMember extends ActiveRecord {
    /**
     * 
     * @var unknown
     */
    const TABLE_NAME = 'activerecordmember_activerecordmember';
    
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
        $columns = array();
        $columns['activerecordmember_id_self'] = 'INT NN';
        $columns['activerecordmember_id_target'] = 'INT NN';
        return $columns;
    }
}
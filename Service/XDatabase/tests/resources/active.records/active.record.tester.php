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
 * 
 * @property int $col_int
 * @property int $col_ai
 */
class ActiveRecordTester extends ActiveRecord {
    /**
     * The table name
     * 
     * @var string
     */
    const TABLE_NAME = 'active_record_tester';
    
    /**
     * 
     * @var unknown
     */
    public $tableName = self::TABLE_NAME;
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::getTableName()
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::describe()
     */
    protected function describe() {
        $describe = array();
        $describe['col_ai'] = 'INT(11) AI PK';
        $describe['col_int'] = 'INT(11)';
        return $describe;
    }
    
    /**
     * 
     */
    public function registerEventHandlerTestHelper($eventName, $handler) {
        $this->registerEventHandler($eventName, $handler);
        return $this->eventHandlers;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::init()
     */
    protected function init() {
        $this->onAfterInsert('afterInsertEventHandler');
        $this->onBeforeFind('beforeFindEventHandler');
        $this->onAfterFind('afterFindEventHandler');
    }
    /**
     * 
     * @var unknown
     */
    public $afterInsertEventExecuted = false;
    
    /**
     * 
     */
    protected function afterInsertEventHandler() {
        $this->afterInsertEventExecuted = true;
    }
    
    /**
     * 
     * @var unknown
     */
    public $beforeFindEventHandler = false;
    
    /**
     * 
     * @var unknown
     */
    public $afterFindEventHandler = false;
    
    /**
     *
     */
    protected function beforeFindEventHandler() {
        $this->beforeFindEventHandler = true;
    }
    
    protected function afterFindEventHandler() {
        $this->afterFindEventHandler = true;
    }
    
    /**
     * 
     * @param unknown $condition
     * @param unknown $limit
     * @return multitype:
     */
    public function doFindTesterHelper($condition=null, $limit=0) {
        return $this->doFind($condition, $limit);
    }
    
    /**
     * 
     * @param unknown $scope
     * @param unknown $condition
     * @return \X\Database\SQL\Condition\Builder
     */
    public function mergeConditionWithScopeHelper($condition) {
        return $this->mergeConditionWithScope($condition);
    }
    
    /**
     * 
     * @var unknown
     */
    const SCOPE_COL_INT_IS_1 = 'col_int_is_1';
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\ActiveRecord\ActiveRecord::scopes()
     */
    protected function scopes() {
        $this->addScope(self::SCOPE_COL_INT_IS_1, array('col_int'=>1));
    }
    
    /**
     * 
     * @param unknown $name
     * @return \X\Database\ActiveRecord\unknown
     */
    public function getScope( $name ) {
        return $this->scopes[$name];
    }
    
    /**
     * 
     */
    public function addScopeTestHelper( $name, $condition ) {
        $this->addScope($name, $condition);
        return $this->getScope($name); 
    }
    
    /**
     * 
     * @param unknown $condition
     * @return \X\Database\ActiveRecord\unknown
     */
    public function addDefaultScopeTestHelper($condition) {
        $this->addDefaultScope($condition);
        return $this->getScope(self::SCOPE_DEFAULT_NAME);
    }
    
    /**
     * 
     */
    public function scopeTestHelper() {
        $this->addDefaultScope(array('col_int'=>3));
        $this->addScope('test-helper-scope-1', array('col_int'=>1));
        $this->addScope('test-helper-scope-2', array('col_int'=>2));
    }
    
    /**
     * 
     * @param unknown $condition
     * @param unknown $limit
     */
    public function doDeleteAllTester($condition, $limit) {
        return $this->doDeleteAll($condition, $limit);
    }
    
    /**
     * 
     * @param unknown $values
     * @param unknown $condition
     * @param number $limit
     */
    public function doUpdateAllTester($values, $condition, $limit=0) {
        return $this->doUpdateAll($values, $condition, $limit);
    }
    
    /**
     * 
     * @return boolean
     */
    public function doSaveUpdateTester() {
        return $this->doSaveUpdate();
    }
    
    /**
     * 
     */
    public function doSaveInsertTester() {
        return $this->doSaveInsert();
    }
    
    public function hasOneTester($target, $getter, $connector) {
        return $this->hasOne($target, $getter, $connector);
    }
}

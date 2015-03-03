<?php
/**
 * Namespace defination
 */
namespace X\Service\XDatabase\Core\ActiveRecord;
/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Util\Exception;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Service\XDatabase\Core\SQL\Func\Count as SQLFuncCount;
use X\Service\XDatabase\Core\SQL\Condition\Condition as SQLCondition;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\ActiveRecord\Query;
use X\Service\XDatabase\Service as XDatabaseService;
use X\Service\XDatabase\Core\SQL\Func\Max;
/**
 * ActiveRecord
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @package X\Database\ActiveRecord
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class XActiveRecord implements \Iterator {
    /**
     * Find records by given criteria and return the matched records.
     * @param Criteria $criteria
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord[]
     */
    private function doFind(Criteria $criteria) {
        $condition = $criteria->condition;
        if ( null !== $criteria->condition && !($criteria->condition instanceof ConditionBuilder ) ) {
            $condition = ConditionBuilder::build($criteria->condition);
        }
        
        $sql = SQLBuilder::build()->select()
            ->from($this->getTableFullName())
            ->where($condition)
            ->limit($criteria->limit)
            ->offset($criteria->position);
        foreach ( $criteria->getOrders() as $order ) {
            $sql->orderBy($order['expression'], $order['order']);
        }
        $result = $this->doQuery($sql->toString());
        
        $class = get_class($this);
        foreach ( $result as $index => $attributes ) {
            $result[$index] = $class::create($attributes, false);
        }
        
        return $result;
    }
    
    /**
     * Convert condition to criteria and return the converted criteria.
     * @param mixed $condition The condition to covert
     * @return \X\Service\XDatabase\Core\ActiveRecord\Criteria
     */
    private function convertConditionToCriteria( $condition ) {
        $criteria = $condition;
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
        }
        return $criteria;
    }
    
    /**
     * Find active records by give condition, the condition could be 
     * anything that able to convert an condition object, it may be an
     * array, a string or a condition object.
     * @see \X\Service\XDatabase\Core\SQL\Condition\Builder
     * @param mixed $condition The condition to find the record.
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord[]
     */
    public function findAll($condition=null) {
        return $this->doFind($this->convertConditionToCriteria($condition));
    }
    
    /**
     * Find an active record by give condition, the condition could be 
     * anything that able to convert an condition object, it may be an
     * array, a string or a condition object. If the active record can 
     * not be found, null will be returned.
     * @see \X\Service\XDatabase\Core\SQL\Condition\Builder
     * @param mixed $condition The condition to find the record.
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    public function find($condition=null) {
        $criteria = $this->convertConditionToCriteria($condition);
        $criteria->limit = 1;
        $result = $this->doFind($criteria);
        return isset($result[0]) ?  $result[0] : null;
    }
    
    /**
     * Find an active record by given primary key. 
     * If the given key does not exists, null would be returned.
     * If there is no primary key defineded, an exception would be throwed.
     * @param string $primaryKey The value of primary key.
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     * @example $this->findByPrimaryKey('00000000-0000-0000-0000-000000000000');
     */
    public function findByPrimaryKey( $primaryKey ) {
        $name = $this->getPrimaryKeyName();
        if ( null === $name ) {
            throw new Exception('Can not find primary key in "'.get_class($this).'"');
        }
        return $this->find(array($name=>$primaryKey));
    }
    
    /**
     * This value contains all the attributes of current object.
     * --key : The name of the attribute.
     * --value : The attribute object.
     * @var array
     */
    private $attributes = array();
    
    /**
     * 检查属性是否存在。
     * @param string $name
     * @return boolean
     */
    public function has( $name ) {
        return  array_key_exists($name, $this->attributes);
    }
    
    /**
     * 根据属性名称获取属性值。
     * @param string $name
     * @return mixed
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception("Attribute '{$name}' does not exists.");
        }
        return $this->attributes[$name]->getValue();
    }
    
    /**
     * @param unknown $attributeName
     * @return string
     */
    public function getAttributeQueryName( $attributeName ) {
        $tableName= $this->getTableFullName();
        $DBDriver = $this->getDb();
        $columnName = $DBDriver->quoteColumnName($attributeName);
        $tableName = $DBDriver->quoteTableName($tableName);
        return $tableName.'.'.$columnName;
    }
    
    /**
     * 根据名称设置属性值
     * @param string $name
     * @param mixed $value
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    public function set( $name, $value ) {
        if ( !$this->has($name) ) {
            throw new Exception("Attribute '{$name}' does not exists.");
        }
        $this->attributes[$name]->setValue($value);
        return $this;
    }
    
    /**
     * @param unknown $values
     */
    public function setAttributeValues ( $values ) {
        foreach ( $values as $key => $value ) {
            $this->set($key, $value);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::__get()
     */
    public function __get( $name ) {
       return $this->get($name);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::__set()
     */
    public function __set( $name, $value ) {
        $this->set($name, $value);
    }
    
    /**
     * 根据名称获取属性对象。
     * @param string $name
     * @return \X\Service\XDatabase\Core\ActiveRecord\Attribute
     */
    public function getAttribute( $name ) {
        return $this->attributes[$name];
    }
    
    /**
     * Get the attribute name list from current active record object.
     * @return array
     */
    public function getAttributeNames() {
        return array_keys($this->attributes);
    }
    
    /**
     * Convert current object to array.
     * @return array
     */
    public function toArray() {
        $attributes = array();
        foreach ( $this->attributes as $name => $attribute ) {
            $attributes[$name] = $attribute->getValue();
        }
        return $attributes;
    }
    
    /**
     * The primary key name of current active record.
     * If the value is null, it means it has not primary key.
     * Notice, you can get this value directly.
     * @see ActiveRecord::getPrimaryKeyName() How to get primary key name
     * @var string
     */
    protected $primaryKeyName = null;
    
    /**
     * Get the primary key name of current active record.
     * If no primary found, null would be returned.
     * @return string|null
     */
    protected function getPrimaryKeyName() {
        if ( null === $this->primaryKeyName ) {
            foreach ($this->attributes as $name => $attribute) {
                if ( $attribute->getIsPrimaryKey() ) {
                    $this->primaryKeyName = $name;
                    break;
                }
            }
            if ( null === $this->primaryKeyName ) {
                $this->primaryKeyName = false;
            }
        }
        return false===$this->primaryKeyName?null:$this->primaryKeyName;
    }
    
    /**
     * Save the changes of current record. if the record is new,
     * it would create a new record, or it would update the exists
     * one, but if there is nothing changed, then it would not be updated.
     * @return boolean
     */
    public function save() {
        if ( !$this->validate() ) {
            throw new Exception('Failed to save XActiveRecord object, Validation failed.');
        }
        
        $this->getIsNew() ? $this->doSaveInsert() : $this->doSaveUpdate();
    }
    
    /**
     * Execute the query to do insert action for saving the
     * current record.
     * @return boolean
     */
    protected function doSaveInsert() {
        $sql = SQLBuilder::build()->insert()
            ->into($this->getTableFullName())
            ->values($this)
            ->toString();
        $this->execute($sql);
        
        foreach ( $this->attributes as $name => $attribute ) {
            if ( $attribute->getIsAutoIncrement() ) {
                $attribute->setValue($this->getDb()->getLastInsertId());
            }
            $attribute->setOldValue($attribute->getValue());
        }
        $this->isNew = false;
    }
    
    /**
     * Save the changes of current model.
     * If there is nothing changed, then it would execute the query to
     * save the object.
     * @return boolean
     */
    protected function doSaveUpdate() {
        $changes = array();
        foreach ( $this->attributes as $name => $attribute ) {
            if ( !$attribute->getIsDirty() ) {
                continue;
            }
            $changes[$name] = $attribute->getValue();
        }
        if ( empty($changes) ) {
            return;
        }
    
        $sql = SQLBuilder::build()->update();
        $sql->table($this->getTableFullName());
        $sql->values($changes);
        $sql->where($this->getRecordCondition());
        $sql->limit(1);
        
        $this->execute($sql->toString());
        
        foreach ( $this->attributes as $name => $attribute ) {
            $attribute->setOldValue($attribute->getValue());
        }
    }
    
    /**
     * Update record by given values with condition.
     * @param array $values The values to records for updating.
     * @param mixed $condition The condition to limitate the effected records.
     * @param integer $limit The number to effect.
     * @return integer The number of updated records
     */
    public function updateAll( $values, $condition=null) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        $results = $this->doFind($condition);
        foreach ( $results as $result ) {
            $result->setAttributeValues($values);
            $result->save();
        }
        return count($results);
    }
    
    /**
     * Delete current active record.
     * @return boolean
     */
    public function delete() {
        $sql = SQLBuilder::build()->delete()
            ->from($this->getTableFullName())
            ->where($this->getRecordCondition())
            ->limit(1)
            ->toString();
        return $this->execute($sql);
    }
    
    /**
     * Delete all records by given condition.
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param mixed $condition The condition to limit the deletion.
     * @param integer $limit The limitation of deleteion.
     * @return integer The number of deleted record.
     */
    public function deleteAll( $condition=null ){
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        $results = $this->doFind($condition);
        foreach ( $results as $result ) {
            $result->delete();
        }
        return count($results);
    }
    
    /**
     * Get the limitiation condition for current active record object.
     * @return string
     */
    protected function getRecordCondition() {
        $primaryKey = $this->getPrimaryKeyName();
        if ( null !== $primaryKey ) {
            $value = $this->getAttribute($primaryKey)->getValue();
            $condition = new SQLCondition($primaryKey, SQLCondition::OPERATOR_EQUAL, $value);
            return $condition;
        }
        else {
            return $this;
        }
    }
    
    /**
     * Count how many matched record by given condition.
     * @param mixed $condition The condition for counting.
     * @return integer The number of matched record.
     */
    public function count( $condition=null ) {
        $sql = SQLBuilder::build()->select()
            ->expression(new SQLFuncCount(), 'count')
            ->from($this->getTableFullName())
            ->where($condition)
            ->toString();
        $result = $this->doQuery($sql);
        return (int)($result[0]['count']);
    }
    
    /**
     * @param unknown $column
     * @param unknown $condition
     */
    public function getMax( $column, $condition=null ) {
        $sql = SQLBuilder::build()->select()
            ->expression(new Max($column), 'max')
            ->from($this->getTableFullName())
            ->where($condition)
            ->toString();
        $result = $this->doQuery($sql);
        return $result[0]['max'];
    }
    
    /**
     * Check whether the record exists by given condition.
     * @param mixed $condition The condition for checking.
     * @return boolean
     */
    public function exists( $condition ) {
        return 0 < $this->count($condition);
    }
    
    /**
     * Execute the given query string. Return true if successed or false
     * if not.
     * @param string $query The query would be executed.
     * @return boolean
     */
    protected function execute( $query ) {
        $this->getDb()->exec($query);
    }
    
    /**
     * Execute the given query string. Return the result of query on successed
     * or false on failed.
     * @param string $query The query would be executed.
     * @return array
     */
    protected function doQuery( $query ) {
        $result = $this->getDb()->query($query);
        return $result;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database
     */
    protected function getDb() {
        /* @var $service XDatabaseService */
        $service = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        return $service->getDatabaseManager()->get();
    }
    
    /**
     * Validate the current active record, and return the validate result.
     * @return boolean
     */
    public function validate() {
        foreach ( $this->attributes as $name => $attribute ) {
            $this->validateAttribute($name);
        }
        return !$this->hasError();
    }
    
    /**
     * Validate the attribute of current active record and return the
     * validate result.
     * @param string $name Then name of attribute.
     * @return boolean
     */
    public function validateAttribute( $name ) {
        $attribute = $this->getAttribute($name);
        if ( !$this->getIsNew() && !$attribute->getIsDirty() ) {
            return;
        }
        
        $isValid = $attribute->validate();
        return $isValid;
    }
    
    /**
     * Check whether there is an error on current active
     * record object.
     * @return boolean
     */
    public function hasError( $name=null ) {
        if ( null !== $name ) {
            return $this->getAttribute($name)->hasError();
        }
        
        foreach ( $this->attributes as $attribute ) {
            if ( $attribute->hasError() ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get the errors on current active record object.
     * If it does, an array contains all errors would be returned.
     * The key of the array is the name of attribute which contains
     * the errors.
     * @return array
     */
    public function getErrors( $name=null ) {
        if ( null !== $name ) {
            return $this->getAttribute($name)->getErrors();
        }
        
        $errors = array();
        foreach ( $this->attributes as $name => $attribute ) {
            if ( $attribute->hasError() ) {
                $errors[$name] = $attribute->getErrors();
            }
        }
        return $errors;
    }
    
    /**
     * @param unknown $attribute
     * @param unknown $error
     * @return \X\Database\ActiveRecord\ActiveRecord
     */
    public function addError( $attribute, $error ) {
        $this->getAttribute($attribute)->addError($error);
        return $this;
    }
    
    /**
     * 初始化
     * @return void
     */
    public function __construct() {
        $this->initAttributesByDeacribe();
        $this->init();
    }
    
    /**
     * @return void
     */
    protected function initAttributesByDeacribe() {
        foreach ( $this->describe() as $name => $attribute ) {
            $attrObject = $attribute;
            if ( !($attrObject instanceof Attribute ) ) {
                if ( is_string($attrObject) ) {
                    $attrObject = new Attribute($name);
                    $attrObject->setupByString($attribute);
                }
            }
            
            $attrObject->setRecord($this);
            $this->attributes[$name] = $attrObject;
        }
    }
    
    /**
     * Describe the columns for current active record object.
     * @return array
     */
    abstract protected function describe();
    
    /**
     * Get the name of the table which current active record map to.
     * @return string
     */
    abstract protected function getTableName();
    
    /**
     * @return string
     */
    protected function getTableNamePrefix() { return ''; }
    
    /**
     * getTableFullName
     * @return string
    */
    public function getTableFullName() {
        return $this->getTableNamePrefix().$this->getTableName();
    }
    
    /**
     * This value use to mark current active record is new or old.
     * @see ActiveRecord::getIsNew() How to check the current is new or old.
     * @see ActiveRecord::setIsNew() How to set "old-new" status of current.
     * @var boolean
     */
    protected $isNew = true;
    
    /**
     * Whether the current active record is new or not.
     * @return boolean
     */
    public function getIsNew() {
        return $this->isNew;
    }
    
    /**
     * Update the "old-new" status of current active record object.
     * @param boolean $isNew
     * @return ActiveRecord
     */
    public function setIsNew( $isNew ) {
        $this->isNew = $isNew;
        return $this;
    }
    
    /**
     * Initilate current active record object.
     * @return void
     */
    protected function init() {}
    
    /**
     * Create a new active record model.
     * @return XActiveRecord
     */
    public static function model() {
        $class = get_called_class();
        return new $class();
    }
    
    /**
     * Create a new active record model. if $attribute is not null,
     * then, it would update the attributes by given attributes.
     * @param array $attributes The value to new object.
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    public static function create( $attributes=null, $isNew=true ) {
        $class = get_called_class();
        $model = new $class();
        $model->setIsNew($isNew);
        
        foreach ( $attributes as $name => $value ) {
            $model->getAttribute($name)->setValue($value);
            if ( !$isNew ) {
                $model->getAttribute($name)->setOldValue($value);
            }
        }
        
        return $model;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public static function query() {
        $class = get_called_class();
        /* @var $object XActiveRecord */
        $object =  new $class();
        $query = new Query();
        $query->addTable($object->getTableFullName());
        return $query;
    }
    
    /**
     * Return the value of current attribute elem.
     *
     * @return mixed
     */
    public function current () {
        $column = current($this->attributes);
        return $column->getValue();
    }
    
    /**
     * Move forward to next attribute element
     * @see Iterator::next()
     * @return mixed
     */
    public function next () {
        return next($this->attributes);
    }
    
    /**
     * Return the key of the current attribute element
     * @see Iterator::key()
     */
    public function key () {
        return key($this->attributes);
    }
    
    /**
     * Checks if current attribute position is valid
     * @see Iterator::valid()
     */
    public function valid () {
        return key($this->attributes) !== null;
    }
    
    /**
     * Rewind the Iterator to the first attribute element
     * @see Iterator::rewind()
     */
    public function rewind () {
        return reset($this->attributes);
    }
}
<?php
/**
 * Namespace defination
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Service\XDatabase\Core\SQL\Func\Count as SQLFuncCount;
use X\Service\XDatabase\Core\SQL\Condition\Condition as SQLCondition;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\SQL\Condition\Query;
use X\Service\XDatabase\Service as XDatabaseService;

/**
 * ActiveRecord
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @package X\Database\ActiveRecord
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class XActiveRecord extends Basic implements \Iterator {
    /*********************** Init the active record *************************/
    public function __construct() {
        $this->initAttributesByDeacribe();
        $this->relationships();
        $this->scopes();
        $this->beforeInit();
        $this->init();
        $this->afterInit();
    }
    
    protected function initAttributesByDeacribe() {
        foreach ( $this->describe() as $column ) {
            $column->setRecord($this);
            $this->columns[$column->getName()] = $column;
        }
    }
    
    /*********************** Attribute Operation *****************************/
    protected $columns = array();
    
    public function has( $name ) {
        return  array_key_exists($name, $this->columns);
    }
    
    public function get( $name ) {
        return $this->columns[$name]->getValue();
    }
    
    public function set( $name, $value ) {
        $this->columns[$name]->setValue($value);
        return $this;
    }
    
    public function __get( $name ) {
        if ( $this->has($name) ) {
            return $this->get($name);
        }
        $relationshipGetter = sprintf('get%s', ucfirst($name));
        if ( isset($this->relationships[$relationshipGetter]) ) {
            return $this->getDataFromRelationship($relationshipGetter);
        }
    
        throw new Exception(sprintf('Can not find getter for "%s" in "%s"', $name, get_class($this)));
    }
    
    public function __set( $name, $value ) {
        $this->set($name, $value);
    }
    
    protected function getAttribute( $name ) {
        return $this->columns[$name];
    }
    
    public function fill( $values ) {
        foreach ( $values as $name => $value ) {
            $this->setAttribute($name, $value);
        }
        return $this;
    }
    
    
    
    
    
    
    
    
    /**
     * Magic caller, You can not call this method directly, but you can
     * treat the name as a method of active record.
     * Now, you can get relationship records by this method.
     * 
     * @see ActiveRecord::relationships() How to add relationships.
     * @param string $name The name of method you gonna call.
     * @param mixed[] $parms An array contains all parameters to that method.
     * @return mixed Return the result of call handler.
     */
    public function __call( $name, $parms ) {
        if ( isset($this->relationships[$name]) ) {
            return $this->getDataFromRelationship($name);
        }
        
        $message = sprintf('Can not find method "%s" in "%s"', $name, get_class($this));
        throw new Exception($message, Exception::CODE_MAGIC_CALL_METHOD_NOT_FOUNT);
    }
    
    /******************** Event Stuff **************************/
    
    /**
     * The name of event before save.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_SAVE = 'OnBeforeSave';
    
    /**
     * The name of event before insert.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_INSERT = 'OnBeforeInsert';
    
    /**
     * The name of event before update.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_UPDATE = 'OnBeoferUpdate';
    
    /**
     * The name of event before validate.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_VALIDATE = 'OnBeoferValidate';
    
    /**
     * The name of event before find.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_FIND = 'OnBeoferFind';
    
    /**
     * The name of event before delete.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_BEFORE_DELETE = 'OnBeforeDelete';
    
    /**
     * The name of event after save.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_SAVE = 'OnAfterSave';
    
    /**
     * The name of event after insert.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_INSERT = 'OnAfterInsert';
    
    /**
     * The name of event after update.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_UPDATE = 'OnAfterUpdate';
    
    /**
     * The name of event after validate.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_VALIDATE = 'OnAfterValidate';
    
    /**
     * The name of event after find.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_FIND = 'OnAfterFind';
    
    /**
     * The name of event after delete.
     * 
     * @see ActiveRecord::triggerEvent() How to trigger event.
     * @var string
     */
    const ON_AFTER_DELETE = 'OnAfterDelete';
    
    /**
     * This value contains all the event handlers for active record.
     * here is an example of the content of this value :
     * <pre>
     * array(
     *      'eventName1' => array($handler1, ...),
     *      ...
     * )
     * </pre>
     * @var array
     */
    protected $eventHandlers = array();
    
    /**
     * Register event handler. You can pass a callable value to this method,
     * also, you can give it a string in your active record class.
     *
     * @param string $eventName The name of the event.
     * @param callback|string $handler The handler for that event.
     * @throws \X\Database\Exception If handler is not callable, an Exception would be throwed.
     * @throws \X\Database\Exception If event name is not supported, an Exception would be throwed.
     * @return void
     */
    protected function registerEventHandler( $eventName, $handler ) {
        $refl = new \ReflectionClass($this);
        $consts = $refl->getConstants();
        if ( false === array_search($eventName, $consts) ) {
            $message = sprintf('Event %s is not supported in ActiveRecord.', is_string($eventName) ? $eventName : 'Unknown');
            throw new \X\Database\Exception($message);
        }
        
        if ( !isset($this->eventHandlers[$eventName]) ) {
            $this->eventHandlers[$eventName] = array();
        }
        
        if ( is_callable($handler) ) {
            // Nothing to do.
        }
        else if ( is_string( $handler ) && method_exists($this, $handler) ) {
            $handler = array($this, $handler);
        }
        else {
            throw new \X\Database\Exception(sprintf('Handler for %s on %s is not callable.', $eventName, get_class($this)));
        }
        
        $this->eventHandlers[$eventName][] = $handler;
    }
    
    /**
     * Register an handler for event after save.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterSave( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_SAVE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event after insert.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterInsert( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_INSERT, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event after update.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterUpdate( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_UPDATE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event after validate.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterValidate( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_VALIDATE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event after find.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterFind( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_FIND, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event after delete.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onAfterDelete( $handler ) {
        $this->registerEventHandler(self::ON_AFTER_DELETE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before save.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeSave( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_SAVE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before insert.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeInsert( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_INSERT, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before update.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeUpdate( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_UPDATE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before validate.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeValidate( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_VALIDATE, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before find.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeFind( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_FIND, $handler);
        return $this;
    }
    
    /**
     * Register an handler for event before delete.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param callback|string $handler The handler to registered.
     * @return ActiveRecord
     */
    protected function onBeforeDelete( $handler ) {
        $this->registerEventHandler(self::ON_BEFORE_DELETE, $handler);
        return $this;
    }
    
    /**
     * Trigger an event to fire all event handlers on that event.
     * 
     * @see ActiveRecord::registerEventHandler() How to register event.
     * @param string $eventName The name of event to trigger.
     * @return void
     */
    public function triggerEvent( $eventName ) {
        if ( !isset($this->eventHandlers[$eventName]) ) {
            return ;
        }
        
        $handlers = $this->eventHandlers[$eventName];
        foreach ( $handlers as $handler ) {
            call_user_func_array($handler, array($this));
        }
    }
    
    /**
     * A handler to trigger after save event handlers.
     * This method is called by save() method.
     * 
     * @see ActiveRecord::save() ActiveRecord::save()
     * @return void
     */
    protected function afterSave() {
        $this->triggerEvent(self::ON_AFTER_SAVE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by doSaveInsert() method.
     * 
     * @see ActiveRecord::doSaveInsert() ActiveRecord::doSaveInsert()
     * @return void
     */
    protected function afterInsert() {
        foreach ( $this->columns as $name => $column ) {
            if ( $column->getIsAutoIncrement() ) {
                $column->setValue($this->getDb()->lastInsertId());
            }
        }
        $this->isNew = false;
        $this->triggerEvent(self::ON_AFTER_INSERT);
    }
    
    /**
     * A handler to trigger after update event handlers.
     * This method is called by doSaveUpdate() method.
     * 
     * @see ActiveRecord::doSaveUpdate() ActiveRecord::doSaveUpdate()
     * @return void
     */
    protected function afterUpdate() {
        $this->triggerEvent(self::ON_AFTER_UPDATE);
    }
    
    /**
     * A handler to trigger after validate event handlers.
     * This method is called by validate(), validateAttribute() method.
     * 
     * @see ActiveRecord::validate() ActiveRecord::validate()
     * @see ActiveRecord::validateAttribute() ActiveRecord::validateAttribute()
     * @return void
     */
    protected function afterValidate() {
        $this->triggerEvent(self::ON_AFTER_VALIDATE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by doFind() method. 
     * But notice, This method would called on result objects,
     * not the object you are using. 
     * 
     * @see ActiveRecord::doFind() ActiveRecord::doFind()
     * @return void
     */
    protected function afterFind() {
        $this->triggerEvent(self::ON_AFTER_FIND);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by delete() method.
     * 
     * @see ActiveRecord::delete() ActiveRecord::delete()
     * @return void
     */
    protected function afterDelete() {
        $this->triggerEvent(self::ON_AFTER_DELETE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by save() method.
     * 
     * @see ActiveRecord::save() ActiveRecord::save()
     * @return void
     */
    protected function beforeSave() {
        $this->triggerEvent(self::ON_BEFORE_SAVE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by doSaveInsert() method.
     * 
     * @see ActiveRecord::doSaveInsert() ActiveRecord::doSaveInsert()
     * @return void
     */
    protected function beforeInsert() {
        $this->triggerEvent(self::ON_BEFORE_INSERT);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by doSaveUpdate() method.
     * 
     * @see ActiveRecord::doSaveUpdate() ActiveRecord::doSaveUpdate()
     * @return void
     */
    protected function beforeUpdate() {
        $this->triggerEvent(self::ON_BEFORE_UPDATE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by validate(), validateAttribute() method.
     * 
     * @see ActiveRecord::validate() ActiveRecord::validate()
     * @see ActiveRecord::validateAttribute() ActiveRecord::validateAttribute()
     * @return void
     */
    protected function beforeValidate() {
        $this->triggerEvent(self::ON_BEFORE_VALIDATE);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by doFind() method.
     * Notice, This event effect the current object, not result object.
     * 
     * @see ActiveRecord::doFind() ActiveRecord::doFind()
     * @return void
     */
    protected function beforeFind() {
        $this->triggerEvent(self::ON_BEFORE_FIND);
    }
    
    /**
     * A handler to trigger after insert event handlers.
     * This method is called by delete() method.
     * 
     * @see ActiveRecord::delete() ActiveRecord::delete()
     * @return void
     */
    protected function beforeDelete() {
        $this->triggerEvent(self::ON_BEFORE_DELETE);
    }
    
    /******************** DMS Stuff **************************/
    
    /**
     * The name of default scope
     * 
     * @see ActiveRecord::addDefaultScope() Add default scope
     * @see ActiveRecord::withScope() ActiveRecord::withScope()
     * @var string
     */
    const SCOPE_DEFAULT_NAME = 'default';
    
    /**
     * This value contains all the definded scopes.
     * Here is an example of this value:
     * <pre>
     *  array(
     *      'scopeName1' => $condition,
     *      ...
     *  )
     * </pre>
     * @var array
     */
    protected $scopes = array();
    
    /**
     * Add scope to active record, The condition parm could be an
     * or a string, also, it could be the instace of class 
     * 'X\Database\SQL\Condition\Builder'
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @see ActiveRecord::withScope() How to use scope 
     * @see ActiveRecord::withoutScope() How to ignore scope 
     * @param string $name The name of the scope
     * @param mixed $condition The condition of the scope
     * @return void
     */
    protected function addScope( $name, $condition ) {
        $this->scopes[$name] = $condition;
    }
    
    /**
     * Add scope to active record as default scope, The condition 
     * parm could be an or a string, also, it could be the instace 
     * of class 'X\Database\SQL\Condition\Builder'
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @see ActiveRecord::withScope() How to use scope 
     * @see ActiveRecord::withoutScope() How to ignore scope 
     * @param mixed $condition
     * @return null
     */
    protected function addDefaultScope( $condition ) {
        $this->addScope(self::SCOPE_DEFAULT_NAME, $condition);
    }
    
    /**
     * Initiate the scope for this active record.
     * This method is called but __contract() method.
     * So, you can define the scope for this object by this method.
     * 
     * @return void
     */
    protected function scopes() {}
    
    /**
     * The name of using scope. If this value is null, then we would 
     * not use scope on find() method. The way to set this value to 
     * null is withScope(null), but there is another way to do it 
     * better, withoutScope().
     * The value changed by withScope() method.
     * 
     * @see ActiveRecord::withScope() How to use scope
     * @see ActiveRecord::withoutScope() How to ignore scope
     * @var string
     */
    protected $usingScope = self::SCOPE_DEFAULT_NAME;
    
    /**
     * Pointed out which scope to use on find. As default, the default 
     * scope would be used, also, you can call it with null to ignore
     * all scope.
     * 
     * @param string $name The name of scope to use.
     * @return ActiveRecord
     */
    public function withScope( $name = self::SCOPE_DEFAULT_NAME ) {
        $this->usingScope = $name;
        return $this;
    }
    
    /**
     * Ignore all scopes on find, Notice, this method just effect the
     * find action once, after find, the scope would be setted to 
     * default scope.
     * 
     * @return ActiveRecord
     */
    public function withoutScope() {
        $this->usingScope = null;
        return $this;
    }
    
    /**
     * Merge the scope into given condition.
     * 
     * @param \X\Database\SQL\Condition\Builder $condition
     * @return \X\Database\SQL\Condition\Builder The condition with scope merged.
     */
    protected function mergeConditionWithScope( $condition ) {
        if ( is_null($this->usingScope) || !isset($this->scopes[$this->usingScope])) {
            return $condition;
        }
    
        if ( is_null($condition) ) {
            $condition = $this->scopes[$this->usingScope];
        }
        else {
            $condition->andAlso()->groupStart()->addCondition($this->scopes[$this->usingScope])->groupEnd();
        }
        return $condition;
    }
    
    /**
     * Find records by given condition, and return active record
     * array as result.
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param mixed $condition The condition to find
     * @param integer $limit The limitation of result
     * @param boolean $triggerEvent Whether trigger event
     * @return ActiveRecord[] The records that matched condition.
     */
    protected function doFind($condition=null, $limit=0, $position=0, $orders=null, $triggerEvent=true) {
        if ( $triggerEvent ) {
            $this->beforeFind();
        }
        
        if ( !is_null($condition) && !($condition instanceof ConditionBuilder ) ) {
            $condition = ConditionBuilder::build($condition);
        }
    
        $condition = $this->mergeConditionWithScope($condition);
    
        $sql = SQLBuilder::build()->select()
            ->from($this->getTableFullName())->where($condition)
            ->limit($limit)->offset($position)->orders($orders)->toString();
        $result = $this->doQuery($sql);
    
        $class = get_class($this);
        foreach ( $result as $index => $attributes ) {
            $result[$index] = $class::create($attributes, false);
            if ( $triggerEvent ) {
                $result[$index]->triggerEvent(self::ON_AFTER_FIND);
            }
        }
    
        $this->usingScope = self::SCOPE_DEFAULT_NAME;
    
        return $result;
    }
    
    /**
     * Find record by given condition, and return active
     * record as result, if no record found, null would
     * be returned.
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param mixed $condition
     * @return ActiveRecord|null
     */
    public function find($condition=null) {
        $result = $this->doFind($condition, 1);
        if ( 0 == \count($result) ) {
            return null;
        }
        return $result[0];
    }
    
    /**
     * Find record by given condition, and return active
     * record as result, if no record found, null would
     * be returned.
     * 
     * @param string $query The where part query string
     * @return ActiveRecord|null
     */
    public function findBySql( $query ) {
        return $this->find($query);
    }
    
    /**
     * Find record by given condition, and return active
     * record as result, if no record found, null would
     * be returned. The key of attributes is the name of 
     * column and the value is you are gonna matched.
     * 
     * @param array $attributes
     * @return ActiveRecord|null
     */
    public function findByAttribute( $attributes ) {
        return $this->find($attributes);
    }
    
    /**
     * Find record by given condition, and return active
     * record as result, if no record found, null would
     * be returned.
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param \X\Database\SQL\Condition\Builder $condition
     * @return ActiveRecord|null
     */
    public function findByCondition( \X\Database\SQL\Condition\Builder $condition ) {
        return $this->find($condition);
    }
    
    /**
     * Find record by given primary key value, if there is not
     * primary key to the active record, an exception would be
     * throwed.
     * 
     * @param mixed $primaryKey
     * @throws \X\Database\Exception Throw an exception if no primary key setted.
     * @return ActiveRecord|null
     */
    public function findByPrimaryKey( $primaryKey ) {
        $pkName = $this->getPrimaryKeyName();
        if ( is_null($pkName) ) {
            throw new Exception(sprintf('Can not find Primary key in %s', get_class($this)));
        }
        return $this->findByAttribute(array($pkName=>$primaryKey));
    }
    
    /**
     * Find records by given condition, and return active record
     * array as result.
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder.
     * @param mixed $condition The condition to find.
     * @param integer $limit The limitation of result.
     * @return ActiveRecord[] The records that matched condition.
     */
    public function findAll($condition=null, $limit=0, $position=0, $orders=null) {
        return $this->doFind($condition, $limit, $position, $orders);
    }
    
    /**
     * Find records by given condition, and return active record
     * array as result.
     * 
     * @param string $query The where part of query
     * @param integer $limit The limitation of result
     * @return ActiveRecord[]
     */
    public function findAllBySql( $query, $limit=0 ) {
        return $this->findAll($query, $limit);
    }
    
    /**
     * Find records by given condition, and return active record
     * array as result.
     * 
     * @param array $attributes The attribute you want to matched
     * @param integer $limit The limitation of result
     * @return ActiveRecord[]
     */
    public function findAllByAttributes( $attributes, $limit=0, $position=0 ) {
        return $this->findAll($attributes, $limit, $position);
    }
    
    /**
     * Find records by given condition, and return active record
     * array as result.
     *
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param mixed $condition The condition to find
     * @param integer $limit The limitation of result
     * @return ActiveRecord[] The records that matched condition.
     */
    public function findAllByCondition( $condition, $limit=0 ) {
        return $this->findAll($condition, $limit);
    }
    
    /**
     * Delete current active record.
     * 
     * @return boolean
     */
    public function delete() {
        $this->beforeDelete();
        $sql = SQLBuilder::build()->delete()
            ->from($this->getTableFullName())
            ->where($this->getRecordCondition())
            ->limit(1)
            ->toString();
        $this->afterDelete();
        return $this->execute($sql);
    }
    
    /**
     * Delete all records by given condition.
     * 
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param mixed $condition The condition to limit the deletion.
     * @param integer $limit The limitation of deleteion.
     * @return integer The number of deleted record.
     */
    protected function doDeleteAll( $condition, $limit ){
        $results = $this->doFind($condition, $limit, 0, null, false);
        foreach ( $results as $result ) {
            $result->delete();
        }
        return count($results);
    }
    
    /**
     * Delete all records by given condition.
     *
     * @param string $query The where part of query
     * @param integer $limit The limitation of deltion.
     * @return integer The record number has been deleted.
     */
    public function deleteAllBySql( $query, $limit=0) {
        return $this->doDeleteAll($query, $limit);
    }
    
    /**
     * Delete all records by given condition.
     * 
     * @param array $attributes
     * @param integer $limit
     * @return integer
     */
    public function deleteAllByAttributes( $attributes, $limit=0 ) {
        return $this->doDeleteAll($attributes, $limit);
    }
    
    /**
     * Delete all records by given condition.
     *
     * @see \X\Database\SQL\Condition\Builder What's Condition builder
     * @param string $condition
     * @param integer $limit The limitation of deltion.
     * @return integer The record number has been deleted.
     */
    public function deleteAllByCondition( $condition, $limit=0 ) {
        return $this->doDeleteAll($condition, $limit);
    }
    
    /**
     * Update record by given values with condition.
     * 
     * @param array $values The values to records for updating.
     * @param mixed $condition The condition to limitate the effected records.
     * @param integer $limit The number to effect.
     * @return integer The number of updated records
     */
    protected function doUpdateAll( $values, $condition, $limit=0 ) {
        $results = $this->doFind($condition, $limit, 0, null, false);
        foreach ( $results as $result ) {
            $result->setAttributes($values);
            $result->save();
        }
        return count($results);
    }
    
    /**
     * Update record by given values with condition.
     * 
     * @param array $values The values to records for updating.
     * @param string $query The where part of query.
     * @param integer $limit The number to effect.
     * @return integer The number of updated records
     */
    public function updateAllBySql( $values, $query, $limit=0 ) {
        return $this->doUpdateAll($values, $query, $limit);
    }
    
    /**
     * Update record by given values with condition.
     * 
     * @param array $values The values to records for updating.
     * @param array $attributes The attributes to match records.
     * @param integer $limit The number to effect.
     * @return integer The number of updated records
     */
    public function updateAllByAttributes( $values, $attributes, $limit=0 ) {
        return $this->doUpdateAll($values, $attributes, $limit);
    }
    
    /**
     * Update record by given values with condition.
     * 
     * @param array $values The values to records for updating. 
     * @param \X\Database\SQL\Condition\Builder $condition The condition of updating.
     * @param integer $limit The number to effect.
     * @return integer The number of updated records
     */
    public function updateAllByCondition( $values, $condition, $limit=0 ) {
        return $this->doUpdateAll($values, $condition, $limit);
    }
    
    /**
     * Save the changes of current model.
     * If there is nothing changed, then it would execute the query to
     * save the object.
     * 
     * @return boolean
     */
    protected function doSaveUpdate() {
        $this->beforeUpdate();
        $changes = array();
        foreach ( $this->columns as $name => $column ) {
            if ( !$column->getIsDirty() ) {
                continue;
            }
            $changes[$name] = $column->getValue();
        }
        if ( empty($changes) ) {
            return;
        }
        
        $sql = SQLBuilder::build()->update()
            ->table($this->getTableFullName())
            ->setValues($changes)
            ->where($this->getRecordCondition())
            ->limit(1)
            ->toString();
        
        $this->execute($sql);
        $this->afterUpdate();
    }
    
    /**
     * Execute the query to do insert action for saving the 
     * current record. 
     * 
     * @return boolean
     */
    protected function doSaveInsert() {
        $this->beforeInsert();
        $sql = SQLBuilder::build()->insert()
            ->into($this->getTableFullName())
            ->values($this)->toString();
        $this->execute($sql);
        $this->afterInsert();
    }
    
    /**
     * Save the changes of current record. if the record is new,
     * it would create a new record, or it would update the exists 
     * one, but if there is nothing changed, then it would not be updated.
     * 
     * @return boolean
     */
    public function save() {
        if ( !$this->validate() ) {
            throw new Exception('Failed to save XActiveRecord object, Validation failed.');
        }
    
        $this->beforeSave();
        $this->getIsNew() ? $this->doSaveInsert() : $this->doSaveUpdate();
        $this->afterSave();
    }
    
    /****************** Attributes ********************/
  
    
    /**
     * Get the attribute name list from current active record object.
     * 
     * @return array
     */
    public function getAttributeNames() {
        return array_keys($this->columns);
    }
    
    /**
     * Convert current object to array.
     * 
     * @return array
     */
    public function toArray() {
        $attributes = array();
        foreach ( $this->columns as $name => $column ) {
            $attributes[$name] = $column->getValue();
        }
        return $attributes;
    }
    
    /**
     * Describe the columns for current active record object.
     * This method returns an array contins the information
     * about each column.
     * 
     * @see \X\Database\ActiveRecord\Column::initColumnByString() How to define the column
     * @return array
     */
    abstract protected function describe();
    
    /**
     * The primary key name of current active record.
     * If the value is null, it means it has not primary key.
     * Notice, you can get this value directly.
     *
     * @see ActiveRecord::getPrimaryKeyName() How to get primary key name
     * @var string
     */
    protected $primaryKeyName = null;
    
    /**
     * Get the primary key name of current active record.
     * If no primary found, null would be returned.
     *
     * @return string|null
     */
    protected function getPrimaryKeyName() {
        if ( !is_null($this->primaryKeyName) ) {
            return $this->primaryKeyName;
        }
    
        foreach ($this->columns as $name => $column) {
            if ( $column->getIsPrimaryKey() ) {
                $this->primaryKeyName = $name;
                break;
            }
        }
    
        return $this->primaryKeyName;
    }
    
    /**
     * Get the limitiation condition for current active record object.
     *
     * @return string
     */
    protected function getRecordCondition() {
        $primaryKey = $this->getPrimaryKeyName();
        if ( !is_null($primaryKey) ) {
            $value = $this->columns[$primaryKey]->getValue();
            $condition = new SQLCondition($primaryKey, SQLCondition::OPERATOR_EQUAL, $value);
            return $condition;
        }
        else {
            return $this;
        }
    }
    
    /**
     * Return the value of current attribute elem.
     *
     * @return mixed
     */
    public function current () {
        $column = current($this->columns);
        return $column->getValue();
    }
    
    /**
     * Move forward to next attribute element
     * 
     * @see Iterator::next()
     * @return mixed
     */
    public function next () {
        return next($this->columns);
    }
    
    /**
     * Return the key of the current attribute element
     * 
     * @see Iterator::key()
     */
    public function key () {
        return key($this->columns);
    }
    
    /**
     * Checks if current attribute position is valid
     * 
     * @see Iterator::valid()
     */
    public function valid () {
        return key($this->columns) !== null;
    }
    
    /**
     * Rewind the Iterator to the first attribute element
     * 
     * @see Iterator::rewind()
     */
    public function rewind () {
        return reset($this->columns);
    }
    
    /**************** Validation *****************************/
    
    /**
     * Validate the current active record, and return the validate
     * result.
     *
     * @return boolean
     */
    public function validate() {
        $this->beforeValidate();
        foreach ( $this->columns as $column ) {
            /* @var $column \X\Service\XDatabase\Core\ActiveRecord\Column */
            if ( $column->getIsDirty() ) {
                $column->validate();
            }
        }
        $this->afterValidate();
    
        return !$this->hasError();
    }
    
    /**
     * Validate the attribute of current active record and return the 
     * validate result.
     *
     * @param string $name Then name of attribute.
     * @return boolean
     */
    public function validateAttribute( $name ) {
        $this->beforeValidate();
        $isValid = $this->columns[ $name ]->validate();
        $this->afterValidate();
        return $isValid;
    }
    
    /**
     * Check whether there is an error on current active
     * record object.
     *
     * @return boolean
     */
    public function hasError() {
        foreach ( $this->columns as $column ) {
            if ( $column->hasError() ) {
                return true;
            }
        }
    
        return false;
    }
    
    /**
     * Check whether there is an error on current active
     * record object.
     * 
     * @param string $name
     */
    public function hasErrorOnAttribute( $name ) {
        return $this->columns[$name]->hasError();
    }
    
    /**
     * Get the errors on current active record object.
     * If it does, an array contains all errors would be returned.
     * The key of the array is the name of attribute which contains
     * the errors.
     * 
     * @return array
     */
    public function getErrors() {
        $errors = array();
        foreach ( $this->columns as $name => $column ) {
            if ( $column->hasError() ) {
                $errors[$name] = $column->getErrors();
            }
        }
        return $errors;
    }
    
    /**
     * Get the errors on given attribute name of  current active record object.
     *
     * @param string $name
     * @return array
     */
    public function getErrorOnAttribute( $name ) {
        return $this->columns[$name]->getErrors();
    }
    
    /**
     * 
     * @param unknown $attribute
     * @param unknown $error
     * @return \X\Database\ActiveRecord\ActiveRecord
     */
    public function addError( $attribute, $error ) {
        $this->columns[$attribute]->addError($error);
        return $this;
    }
    
    /*************** Relationship Stuff*************************/
    /**
     * The name of relationship "has one"
     * 
     * @see ActiveRecord::hasOne() How to add relationship "has one"
     * @var string
     */
    const HAS_ONE = 'HasOne';
    
    /**
     * The name of relationship "has many"
     * 
     * @see ActiveRecord::hasMany() How to add relationship "has one"
     * @var string
     */
    const HAS_MANY = 'HasMany';
    
    /**
     * The name of relationship "belongs to"
     * 
     * @see ActiveRecord::belongsTo() How to add relationship "belongs to"
     * @var string
     */
    const BELONGS_TO = 'BelongsTo';
    
    /**
     * The name of relationship "many to many"
     * 
     * @see ActiveRecord::manyToMany() How to add relationship "many to many"
     * @var string
     */
    const MANY_TO_MANY = 'ManyToMany';
    
    /**
     * This value contains all the relationship information about current
     * active record object. The key is the getter name of relationship, and
     * the value is the information about the relationship.
     * 
     * @var array
     */
    protected $relationships = array();
    
    /**
     * Get the target information by given taget name, if the target does not 
     * exists, it would try to use cuurent active record's namespace on target
     * and then try again.
     * If this method successed, an array contains target full class name and 
     * short name would be returned.
     * 
     * @param string $target The class name of target.
     * @throws \X\Database\Exception Throw an exception if target can not be found.
     * @return array
     */
    protected function getRelationshipTargetName( $target ) {
        if ( !class_exists($target, false) ) {
            $target = sprintf('%s\\%s', $this->getNamespaceName(), $target);
        }
        
        if ( !class_exists($target, false) ) {
            throw new \X\Database\Exception('Can not find target Active record.');
        }
        
        $class = ($target == get_class($this)) ? $this : new $target();
        return array($target, $class->getClassName());
    }
    
    /**
     * Add has one relationship to current active record object.
     * 
     * @param string $target The class name of target class
     * @param string $getter The getter name for this relationship.
     * @param string $connector The connector name for this relationship.
     * @return ActiveRecord
     */
    protected function hasOne( $target, $getter=null, $connector=null ) {
        list($target, $targetClass) = $this->getRelationshipTargetName($target);
        
        $getter = is_null($getter) ? sprintf('get%s', $targetClass) : $target;
        $connector = is_null($connector) ? sprintf('%s_id', strtolower($this->getClassName())) : $connector;
        $this->relationships[$getter] = array('type'=>self::HAS_ONE,'target'=>$target, 'connector'=>$connector);
        return $this;
    }
    
    /**
     * Get data from relationship "has one"
     * 
     * @see ActiveRecord::hasOne() How to add "Has one" relationship.
     * @param array $information The inforation about relationship
     * @return ActiveRecord
     */
    protected function getFromRelationshipHasOne( $information ) {
        $target = $information['target'];
        $connector = $information['connector'];
        $result = $target::model()->find(array($connector => $this->getAttribute($this->getPrimaryKeyName())));
        return $result;
    }
    
    /**
     * Add has one relationship to current active record object.
     * 
     * @param string $target The class name of target class
     * @param string $getter The getter name for this relationship.
     * @param string $connector The connector name for this relationship.
     * @return ActiveRecord
     */
    protected function hasMany( $target, $getter=null, $connector=null) {
        list($target, $targetClass) = $this->getRelationshipTargetName($target);
        $getter = is_null($getter) ? sprintf('getAll%s', $targetClass) : $getter;
        $connector = is_null($connector) ? sprintf('%s_id', strtolower($this->getClassName()), $target) : $connector;
        $this->relationships[$getter]=array('type'=>self::HAS_MANY, 'target'=>$target, 'connector'=>$connector);
        return $this;
    }
    
    /**
     * Get data from relationship "has many"
     * 
     * @see ActiveRecord::hasMany() How to add "Has many" relationship.
     * @param array $information The inforation about relationship
     * @return ActiveRecord[]
     */
    protected function getFromRelationshipHasMany( $information ) {
        $target = $information['target'];
        $connector = $information['connector'];
        $result = $target::model()->findAll(array($connector => $this->getAttribute($this->getPrimaryKeyName())));
        return $result;
    }
    
    /**
     * Add "many to many" relationship to current active record object.
     * This kind of relationship requires another table for help.
     *
     * @param string $target The class name of target class
     * @param string $getter The getter name for this relationship.
     * @param string $connector The connector class name for this relationship.
     * @param string $connectorSelf The connector name of current object.
     * @param string $connectorTarget The connector name of target object.
     * @return ActiveRecord
     */
    protected function manyToMany( $target, $getter=null, $connector=null, $connectorSelf=null, $connectorTarget=null ) {
        list($target, $targetClass) = $this->getRelationshipTargetName($target);
        
        $getter = is_null($getter) ? sprintf('getAll%s', $targetClass) : $getter;
        $connector = is_null($connector) ? sprintf('%s%s', $this->getClassName(), $targetClass) : $connector;
        $connector = $this->getRelationshipTargetName($connector);
        $connector = $connector[0];
        $connectorSelf = is_null($connectorSelf) ? sprintf('%s_id', strtolower($this->getClassName())) : $connectorSelf;
        $connectorTarget = is_null($connectorTarget) ? sprintf('%s_id', strtolower($targetClass)) : $connectorTarget;
        if ( $connectorSelf == $connectorTarget ) {
            $connectorSelf .= '_self';
            $connectorTarget .= '_target';
        }
        
        $this->relationships[$getter]=array(
                'type'=>self::MANY_TO_MANY, 'target'=>$target, 'connectorSelf'=>$connectorSelf,
                'connectorTarget'=>$connectorTarget, 'connector'=>$connector);
        return $this;
    }
    
    /**
     * Get data from relationship "many to many"
     * 
     * @see ActiveRecord::manyToMany() How to add "many to many" relationship.
     * @param array $information The inforation about relationship
     * @return ActiveRecord[]
     */
    protected function getFromRelationshipManyToMany( $information ) {
        $connector = $information['connector'];
        $attributes = array();
        $attributes[$information['connectorSelf']] = $this->getAttribute($this->getPrimaryKeyName());
        $results = $connector::model()->findAllByAttributes($attributes);
        $list = array();
        foreach ( $results as $result ) {
            $list[] = $result->getAttribute($information['connectorTarget']);
        }
        $target = $information['target'];
        $attributes = array();
        $attributes[$target::model()->getPrimaryKeyName()] = $list;
        $results = $target::model()->findAllByAttributes($attributes);
        return $results;
    }
    
    /**
     * Add "belongs to" relationship to current active record object.
     * 
     * @param string $target The class name of target class
     * @param string $getter The getter name for this relationship.
     * @param string $connector The connector name for this relationship.
     * @return ActiveRecord
     */
    protected function belongsTo( $target, $getter=null, $connector=null ) {
        list($target, $targetClass) = $this->getRelationshipTargetName($target);
        $getter = is_null($getter) ? sprintf('get%s', $targetClass) : $getter;
        $connector = is_null($connector) ? sprintf('%s_id', strtolower($targetClass)) : $connector;
        $this->relationships[$getter] = array('type'=>self::BELONGS_TO, 'target'=>$target, 'connector'=>$connector);
        return $this;
    }
    
    /**
     * Get data from relationship "belongs to"
     * 
     * @see ActiveRecord::belongsTo() How to add "belongs to" relationship.
     * @param array $information The inforation about relationship
     * @return ActiveRecord
     */
    protected function getFromRelationshipBelongsTo( $information ) {
        $target = $information['target'];
        $connector = $information['connector'];
        $attributes = array();
        $attributes[$result = $target::model()->getPrimaryKeyName()] = $this->getAttribute($connector);
        $result = $target::model()->findByAttribute($attributes);
        return $result;
    }
    
    /**
     * Get data from relationship, this method is called by magic getter and 
     * magic caller for quick way to get data from relationships.
     * 
     * @param string $getter The getter name of relationship.
     * @return mixed
     */
    protected function getDataFromRelationship( $getter ) {
        $information = $this->relationships[$getter];
        $getter = sprintf('getFromRelationship%s', $information['type']);
        return $this->$getter($information);
    }
    
    /**
     * Definds the relationship for current active record object.
     * 
     * @return void
     */
    protected function relationships() {}
    
    /***************** Information ********************************/
    /**
     * 
     * @return string
     */
    protected function getTableNamePrefix() { return ''; }
    
    /**
     * Get the name of the table which current active record map to.
     *
     * @return string
     */
    abstract protected function getTableName();
    
    /**
     * getTableFullName
     * 
     * @return string
     */
    public function getTableFullName() {
        return $this->getTableNamePrefix().$this->getTableName();
    }
    
    /**
     * This value use to mark current active record is new or old.
     * 
     * @see ActiveRecord::getIsNew() How to check the current is new or old.
     * @see ActiveRecord::setIsNew() How to set "old-new" status of current.
     * @var boolean
     */
    protected $isNew = true;
    
    /**
     * Whether the current active record is new or not.
     *
     * @return boolean
     */
    public function getIsNew() {
        return $this->isNew;
    }
    
    /**
     * Update the "old-new" status of current active record object.
     *
     * @param boolean $isNew
     * @return ActiveRecord
     */
    public function setIsNew( $isNew ) {
        $this->isNew = $isNew;
        return $this;
    }
    
    /**
     * Count how many matched record by given condition.
     *
     * @param mixed $condition The condition for counting.
     * @return integer The number of matched record.
     */
    public function count( $condition=null ) {
        $sql = SQLBuilder::build()->select()
            ->columns(array('count'=>new SQLFuncCount()))
            ->from($this->getTableFullName())
            ->where($condition)
            ->toString();
    
        $result = $this->doQuery($sql);
        return $result[0]['count'];
    }
    
    /**
     * Check whether the record exists by given condition.
     * 
     * @param mixed $condition The condition for checking.
     * @return boolean
     */
    public function exists( $condition ) {
        return 0 < $this->count($condition);
    }
    
    /***************** Builders ***************************************/
    
    /**
     * Create a new active record model.
     *
     * @return XActiveRecord
     */
    public static function model() {
        $class = get_called_class();
        return new $class();
    }
    
    /**
     * Create a new active record model. if $attribute is not null,
     * then, it would update the attributes by given attributes.
     *
     * @param array $attributes The value to new object.
     * @return ActiveRecord
     */
    public static function create( $attributes=null, $isNew=true ) {
        $class = get_called_class();
        $model = new $class();
        $model->setIsNew($isNew);
        
        if ( null === $model ) {
            return $model;
        }
        
        foreach ( $attributes as $name => $value ) {
            $model->getAttribute($name)->setValue($value);
            if ( !$isNew ) {
                $model->getAttribute($name)->setOldValue($value);
            }
        }
        
        return $model;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public static function query() {
        $class = get_called_class();
        /* @var $object XActiveRecord */
        $object =  new $class();
        $query = new Query();
        $query->setTables(array($object->getTableFullName()));
        return $query;
    }
    
    /************* Helpers **************************/
    
    /**
     * 
     */
    protected function beforeInit() {}
    
    /**
     * 
     */
    protected function afterInit() {}
    
    /**
     * Initilate current active record object.
     *
     * @return void
     */
    protected function init() {}
    
    /**
     * Execute the given query string. Return true if successed or false
     * if not.
     * 
     * @param string $query The query would be executed.
     * @return boolean
     */
    protected function execute( $query ) {
        $this->getDb()->exec($query);
    }
    
    /**
     * Execute the given query string. Return the result of query on successed
     * or false on failed.
     *  
     * @param string $query The query would be executed.
     * @return array
     */
    protected function doQuery( $query ) {
        $result = $this->getDb()->query($query);
        if ( false === $result ) {
            throw new Exception(sprintf('Failed to execute query: %s', $query));
        }
        return $result;
    }
    
    /**
     * @return \X\Service\XDB\Core\Database
     */
    protected function getDb() {
        return X::system()->getServiceManager()->get(XDatabaseService::SERVICE_NAME)->getDb();
    }
    
    /**
     * Return the namespace name of current object
     * 
     * @return string
     */
    public function getNamespaceName() {
        $class = new \ReflectionClass($this);
        return $class->getNamespaceName();
    }
    
    /**
     * Return the short class name without namespace name of current object.
     * 
     * @return string
     */
    public function getClassName() {
        $class = new \ReflectionClass($this);
        return $class->getShortName();
    }
}
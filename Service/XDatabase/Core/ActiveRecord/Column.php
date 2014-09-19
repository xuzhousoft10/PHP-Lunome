<?php
/**
 * Namespace defination
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

use X\Service\XDatabase\Core\Basic;
use X\Library\XData\XString;
/**
 * Column
 * 
 * @author Michael Luthor <michael.the.ranidae@gmail.com>
 * @package \X\Database\ActiveRecord
 * @since 0.0.0
 * @version 0.0.0
 */
class Column extends Basic {
    /**
     * Initiate the Column by given information.
     * 
     * @param ActiveRecord $record The record that this Column belongs to.
     * @param string $name The name of the Column.
     * @param string $info -- The string to describe the Column.
     */
    public function __construct( $record, $name, $info ) {
        $this->record = $record;
        $this->name = $name;
        $this->initColumn($info);
    }
    
    /**
     * PHP magic method colone, reinitiate the Column when clone it.
     */
    public function __clone() {
        $reflect = new \ReflectionClass($this);
        $type = \ReflectionProperty::IS_PUBLIC 
                | \ReflectionProperty::IS_PROTECTED
                | \ReflectionProperty::IS_PRIVATE;
        
        foreach ( $reflect->getProperties($type) as $prop ) {
            $name = $prop->getName();
            if ( !is_object($this->$name) ) continue;
            $this->$name = clone $this->$name;
        }
    }
    
    /**
     * Initiate the Column by given information
     * 
     * @param mixed $information 
     */
    protected function initColumn( $information ) {
        if ( is_string($information) ) {
            $this->initColumnByString($information);
        }
    }
    
    /**
     * Initiate the Column by string information.
     * 
     * Key words for initialization.
     * <pre>
     * Data type        : INT|VARCHAR|DATETIME
     * Length           : ()
     * Primary Key      : PK
     * Not Null         : NN
     * Unsigned         : UN
     * Auto Increase    : AI
     * Default Value    : ""
     * </pre>
     * 
     * @param string $information The Column string information.
     * @return void
     */
    protected function initColumnByString($information) {
        if (false !== strpos($information, 'PK')) {
            $this->isPrimaryKey = true;
            $this->isNotNull = true;
            $information = str_replace('PK', '', $information);
        }
        if ( false !== strpos($information, 'NN') ) {
            $this->isNotNull = true;
            $information = str_replace('NN', '', $information);
        }
        if ( false !== strpos($information, 'UN') ) {
            $this->isUnsigned = true;
            $information = str_replace('UN', '', $information);
        }
        if ( false !== strpos($information, 'AI') ) {
            $this->isAutoIncrease = true;
            $information = str_replace('AI', '', $information);
        }
        if ( false !== strpos($information,'UQ') ) {
            $this->isUnique = true;
            $information = str_replace('UQ', '', $information);
        }
        
        preg_match('/(INT|VARCHAR|DATETIME)/', $information, $type);
        $this->type = $type[1];
        $information = str_replace($type[1], '', $information);
        
        if ( preg_match('/\((\d+)\)/', $information, $length) ) {
            $this->length = $length[1];
            $information = str_replace($length[0], '', $information);
        }
        
        $information = trim($information);
        if ( false !== strpos($information, '"') ) {
            $information = substr($information, 1);
            $information = substr($information, 0, strlen($information)-1);
            $this->defaultValue = $information;
        }
    }
    
    /**
     * The active record that this Column belongs to.
     * 
     * @see Column::setRecord() How to set the record for Column
     * @var ActiveRecord
     */
    protected $record = null;
    
    /**
     * Set the host record for current Column object.
     * 
     * @param ActiveRecord $record The host active record
     * @return Column
     */
    public function setRecord( $record ) {
        $this->record = $record;
        return $this;
    }
    
    /**
     * The name of current Column object.
     * 
     * @var string
     */
    protected $name = null;
    
    /**
     * The name of current Column object's type.
     * 
     * @var string
     */
    protected $type = null;
    
    /**
     * The length of current Column object's value.
     * 
     * @var integer
     */
    protected $length = null;
    
    /**
     * 
     * @return number
     */
    public function getLength() {
        return $this->length;
    }
    
    /**
     * The min length of current Column object's value.
     * 
     * @see Column::setMinLength() How to set min length for Column object.
     * @var integer
     */
    protected $minLength = null;
    
    /**
     * Set the min length for current Column object's value.
     * 
     * @param integer $length The min length
     * @return Column
     */
    public function setMinLength( $length ) {
        $this->minLength = $length;
        return $this;
    }
    
    /**
     * Whether the current Column object's value could be empty.
     * 
     * @see Column::setEmptiable() How to set emptyablre for Column object
     * @var boolean
     */
    protected $emptiable = true;
    
    /**
     * Set whether the Column could be emptyable.
     * 
     * @param boolean $emptiable
     * @return Column
     */
    public function setEmptiable( $emptiable ) {
        $this->emptiable = $emptiable;
        return $this;
    }
    
    /**
     * Wether this Column is a primary key
     * 
     * @see Column::getIsPrimaryKey() How to check the primary key.
     * @var boolean
     */
    protected $isPrimaryKey = false;
    
    /**
     * Get whether the current Column is primary key.
     * 
     * @return boolean
     */
    public function getIsPrimaryKey() {
        return $this->isPrimaryKey;
    }
    
    /**
     * Wether is unique or not.
     * 
     * @var boolean
     */
    protected $isUnique = false;
    
    /**
     * Whether the value of current Column object's can be null.
     * 
     * @var boolean
     */
    protected $isNotNull = false;
    
    /**
     * Whether the value of current Column object is binary
     * 
     * @var boolean
     */
    protected $isBinary = false;
    
    /**
     * Whether the value of current Column object is unsigned.
     * 
     * @var boolean
     */
    protected $isUnsigned = false;
    
    /**
     * Whether the value of current Column object is auto increase.
     * 
     * @see Column::getIsAutoIncrease() How to check whether the Column is auto increase.
     * @var boolean
     */
    protected $isAutoIncrease = false;
    
    /**
     * Get whether the value of current Column object is auto increase.
     * 
     * @return boolean
     */
    public function getIsAutoIncrease() {
        return $this->isAutoIncrease;
    }
    
    /**
     * The default value of current Column object.
     * 
     * @var mixed
     */
    protected $defaultValue = null;
    
    /**
     * The value of current Column object.
     * 
     * @see Column::setValue() How to set value to Column.
     * @see Column::getValue() How to get value from Column.
     * @var mixed
     */
    protected $value = null;
    
    /**
     * The old value of current Column object.
     * 
     * @var mixed
     */
    protected $oldValue = null;
    
    /**
     * Update the value of conlum.
     * 
     * @param mixed $value The value to current Column object
     * @return Column
     */
    public function setValue( $value ) {
        if ( $this->record->getIsNew() ) {
            $this->oldValue = $value;
        }
        
        $this->value = $value;
        return $this;
    }
    
    /**
     * 
     * @var callback
     */
    protected $valueBuilder = null;
    
    /**
     * 
     * @param unknown $builder
     * @return \X\Service\XDB\ActiveRecord\Column
     */
    public function setValueBuilder( $builder ) {
        $this->valueBuilder = $builder;
        return $this;
    }
    
    /**
     * Get value from Column object.
     * 
     * @param boolean $quote Whether quote the value.
     * @return mixed
     */
    public function getValue( $quote=false ) {
        $value = null;
        if ( !is_null($this->value) ) {
            $value = $this->value;
        } else if ( !is_null($this->valueBuilder) ) {
            $value = call_user_func_array($this->valueBuilder, array($this->record));
        } else {
            $value = $this->defaultValue;
        }
        $this->value = $value;
        return $value;
        //return $quote ? $this->quoteValue() : ( is_null($this->value) ? $this->defaultValue : $this->value );
    }
    
    /**
     * Quote current Column's value
     * 
     * @return string
     */
    protected function quoteValue( ) {
        if ( !is_null($this->value) ) {
            return \X\Database\Management::getManager()->getDb()->quote($this->value);
        }
        else if ( $this->isAutoIncrease ) {
            return "''";
        }
        else if ( !is_null($this->defaultValue) ) {
            return \X\Database\Management::getManager()->getDb()->quote($this->defaultValue);
        }
        else {
            return 'NULL';
        }
    }
    
    /**
     * Get whether the value of Column has been modified.
     * 
     * @return boolean
     */
    public function isDirty() {
        return $this->oldValue != $this->value;
    }
    
    /**
     * Clean the dirty status, set the value to new value.
     * 
     * @return Column
     */
    public function cleanDirty() {
        $this->value = $this->oldValue;
        return $this;
    }
    
    /**
     * Clean the dirty status, and set value to old value
     * 
     * @return Column
     */
    public function refresh() {
        $this->oldValue = $this->value;
        return $this;
    }
    
    /**
     * This value contains all errors on current Column object. 
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Add error to current Column object.
     * 
     * @param string $message
     * @return Column
     */
    public function addError( $message ) {
        $this->errors[] = $message;
        return $this;
    }
    
    /**
     * Get whether there is an error on current Column obejct.
     * 
     * @return boolean
     */
    public function hasError() {
        return 0 < count($this->errors);
    }
    
    /**
     * Get the errors on current Column object.
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * This value contains all custome validators for current Column object.
     * 
     * @var callback[]
     */
    protected $validators = array();
    
    /**
     * Add custome validator to current Column obejct.
     * 
     * @param callback $validator
     * @return Column
     */
    public function addValidator( $validator ) {
        $this->validators[] = $validator;
        return $this;
    }
    
    /**
     * Validate current Column object, and return validate result.
     * 
     * @return boolean
     */
    public function validate() {
        $this->errors = array();
        
        if ( $this->isAutoIncrease ) {
            return true;
        }
        
        $value = $this->getValue();
        
        $validateItems = array(
            'NotNull',  'DataType',     'Emptiable',    'Length', 
            'Unique',   'PrimaryKey',   'Unsigned');
        
        foreach ( $validateItems as $item ) {
            $handler = sprintf('validate%s', $item);
            if ( false === $this->$handler($value) ) {
                return false;
            }
        }
        
        $hasValidateError = false;
        foreach ( $this->validators as $validator ) {
            if ( false === call_user_func_array($validator, array($this)) ) {
                $hasValidateError = true;
            }
        }
        
        return !$hasValidateError;
    }
    
    /**
     * "NOT NULL" check for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateNotNull( $value ) {
        if ( $this->isNotNull && is_null($value)) {
            $this->addError(sprintf('"%s" can not be empty.', $this->name));
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data type for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataType( $value ) {
        $handler = sprintf('validateDataType%s', ucfirst(strtolower($this->type)));
        if ( method_exists($this, $handler) ) {
            return call_user_func_array(array($this, $handler), array($value));
        }
        else {
            $this->addError('Unknown type "%s" of Column "%s".', $this->type, $this->name);
            return false;
        }
    }
    
    /**
     * Validate the data type integer for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeInt( $value ) {
        if ( !$this->isNotNull && is_null($value) ) {
            return true;
        }
        
        if ( !is_numeric($value) ) {
            $this->addError(sprintf('The value of "%s" is not a validated integer.', $this->name));
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data type varchar for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeVarchar( $value ) {
        $validate = is_string($value) || ( is_object($value) && method_exists($value, '__toString') );
        if ( !$validate ) {
            $this->addError(sprintf('The value of "%s" is not a validated string.', $this->name));
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data type for datetime current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeDatetime( $value ) {
        if ( false === \DateTime::createFromFormat('Y-m-d H:i:s', $value) ) {
            $this->addError(sprintf('The value of "%s" is not a validated date and time.', $this->name));
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data length for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateLength( $value ) {
        if ( 'VARCHAR' !== strtoupper($this->type) ) {
            return true;
        }
        
        $length = XString::str($value)->getLength();
        if ( !is_null($this->length) && $length > $this->length ) {
            $this->addError(sprintf('The value "%s" of "%s" is too long.', $value, $this->name));
            return false;
        }
        
        if ( !is_null($this->minLength) && $length < $this->minLength ) {
            $this->addError(sprintf('The value "%s" of "%s" is too short.', $value, $this->name));
            return false;
        }
        
        return true;
    }
    
    /**
     * Unique check for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateUnique( $value ) {
        if ( !$this->record->getIsNew() && !$this->isDirty() ) {
            return true;
        }
        
        if( $this->isUnique && $this->record->exists(array($this->name=>$value)) ) {
            $this->addError(sprintf('"%s" already exists.', $value));
            return false;
        }
        
        return true;
    }
    
    /**
     * Primary key check for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validatePrimaryKey( $value ) {
        if( $this->isPrimaryKey && $this->isDirty() && $this->record->exists(array($this->name=>$value)) ) {
            $this->addError(sprintf('The key "%s" of "%s" already exists.', $value, get_class($this->record)));
            return false;
        }
        
        return true;
    }
    
    /**
     * Unsigned check for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateUnsigned( $value ){
        if ( 'INT' === strtoupper($this->type) && 0 > $value ) {
            $this->addError(sprintf('The value "%s" of "%s" can not lower that 0.', $value, $this->name));
            return false;
        }
    }
    
    /**
     * Empty check for current Column object.
     * 
     * @param mixed $value
     * @return boolean
     */
    protected function validateEmptiable( $value ) {
        if ( !$this->emptiable && 0 === strlen($value)) {
            $this->addError(sprintf('"%s" can not be empty.', $this->name));
            return false;
        }
        return true;
    }
}
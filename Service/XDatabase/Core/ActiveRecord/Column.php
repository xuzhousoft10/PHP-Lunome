<?php
/**
 * 
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

/**
 * 
 */
use X\Core\X;
use X\Library\XData\XString;
use X\Service\XDatabase\Core\Table\Column as TableColumn;

/**
 * 
 */
class Column extends TableColumn {
    /**
     * @var \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    protected $record = null;
    
    /**
     * @param unknown $record
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setRecord( $record ) {
        $this->record = $record;
        return $this;
    }
    
    /**
     * @var unknown
     */
    protected $attachAttributes = array(
        'minLength'     => null,
        'isUnique'      => false,
    );
    
    /**
     * 
     * @param unknown $name
     */
    public function __construct($name) {
        $this->attributes = array_merge($this->attributes, $this->attachAttributes);
        parent::__construct($name);
    }
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setMinLength( $length ) {
        return $this->set('minLength', $length);
    }
    
    public function getMinLength() {
        return $this->get('minLength');
    }
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setIsUnique($isUnique) {
        return $this->set('isUnique', $isUnique);
    }
    
    public function getIsUnique() {
        return $this->get('isUnique');
    }
    
    protected $valueBuilder = null;
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setValueBuilder( $builder ) {
        $this->valueBuilder = $builder;
        return $this;
    }
    
    protected $newValue = null;
    protected $oldValue = null;
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setValue( $value ) {
        $this->newValue = $value;
        return $this;
    }
    
    /**
     * Get value from Column object.
     *
     * @return mixed
     */
    public function getValue() {
        $value = null;
        if ( null !== $this->newValue ) {
            $value = $this->newValue;
        } else if ( null !== $this->valueBuilder && is_callable($this->valueBuilder) ) {
            $value = call_user_func_array($this->valueBuilder, array($this->record));
        } else {
            $value = $this->getDefault();
        }
        $this->newValue = $value;
        return $value;
    }
    
    /**
     * Get whether the value of Column has been modified.
     * 
     * @return boolean
     */
    public function getIsDirty() {
        return $this->oldValue !== $this->newValue;
    }
    
    /**
     * Clean the dirty status, set the value to new value.
     * 
     * @return Column
     */
    public function cleanDirty() {
        $this->newValue = $this->oldValue;
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
        $this->errors[] = call_user_func_array('sprintf', func_get_args());
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
        
        if ( $this->getIsAutoIncrement() ) {
            return true;
        }
        
        $value = $this->getValue();
        
        $validateItems = array(
            'NotNull',  'DataType',     'Length', 
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
        if ( $this->getNullable() ) {
            return true;
        }
        if ( null === $value ) {
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
        if ( null === $value ) {
            return true;
        }
        
        if ( !is_int($value) ) {
            $this->addError(sprintf('The value of "%s" is not a validated integer.', $this->name));
            return false;
        }
        return true;
    }
    
    protected function validateDataTypeTinyint($value) {
        if ( null === $value ) {
            return true;
        }
        
        if ( !is_int($value) 
        || ( ($this->getIsUnsigned()) ? ($value < 0 || 255 < $value) : ( $value < -128 || 128 < $value ) )
        ) {
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
        $type = strtoupper($this->getType());
        if ( 'VARCHAR' !== $type && 'CHAR' !== $type ) {
            return true;
        }
        
        $length = $this->getLength();
        $valLength = XString::str($value)->getLength();
        if ( null !== $length && $valLength > $length ) {
            $this->addError(sprintf('The value "%s" of "%s" is too long.', $value, $this->name));
            return false;
        }
        
        $minLength = $this->getMinLength();
        if ( null !== $minLength && $valLength < $minLength ) {
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
        if ( !$this->record->getIsNew() && !$this->getIsDirty() ) {
            return true;
        }
        
        if( $this->getIsUnique() && $this->record->exists(array($this->name=>$value)) ) {
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
        if( $this->getIsPrimaryKey()
        &&  $this->getIsDirty() 
        &&  $this->record->exists(array($this->getName()=>$value)) ) {
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
        $unsignedTypes = array('TINYINT', 'SMALLINT','INT', 'BIGINT');
        $type = strtoupper($this->getType());
        if ( in_array($type, $unsignedTypes) && 0 > $value ) {
            $this->addError(sprintf('The value "%s" of "%s" can not lower that 0.', $value, $this->name));
            return false;
        }
        return true;
    }
}
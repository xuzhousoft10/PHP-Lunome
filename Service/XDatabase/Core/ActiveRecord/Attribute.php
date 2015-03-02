<?php
/**
 * 
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

/**
 * 
 */
use X\Core\X;
use X\Service\XDatabase\Core\Table\Column as TableColumn;

/**
 * 
 */
class Attribute extends TableColumn {
    /**
     * @param string $description
     */
    public function setupByString( $description ) {
        $description = str_getcsv($description, ' ', '"');
        while ( null !== ( $item=array_pop($description) ) ) {
            switch ( strtoupper($item) ) {
            case 'PRIMARY'  : $this->setIsPrimaryKey(true); break;
            case 'UNSIGNED' : $this->setIsUnsigned(true); break;
            case 'UNIQUE'   : $this->setIsUnique(true); break;
            case 'NOTNULL'  : $this->setNullable(false); break;
            default:
                if ( ('[' === $item[0]) && (']' === $item[strlen($item)-1]) ) {
                    $this->setDefault(substr($item, 1, strlen($item)-2));
                } else {
                    $type = explode('(', $item);
                    $this->setType($type[0]);
                    if ( isset($type[1]) ) {
                        $this->setLength(substr($type[1], 0, strlen($type[1])-1));
                    }
                }
                break;
            }
        }
    }
    
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
        return $this->set('minLength', (int)$length);
    }
    
    /**
     * @return Ambigous <\X\Service\XDatabase\Core\Table\mixed, multitype:>
     */
    public function getMinLength() {
        return $this->get('minLength');
    }
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setIsUnique($isUnique) {
        return $this->set('isUnique', $isUnique);
    }
    
    /**
     * @return Ambigous <\X\Service\XDatabase\Core\Table\mixed, multitype:>
     */
    public function getIsUnique() {
        return $this->get('isUnique');
    }
    
    /**
     * @var unknown
     */
    protected $valueBuilder = null;
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setValueBuilder( $builder ) {
        $this->valueBuilder = $builder;
        return $this;
    }
    
    /**
     * @var unknown
     */
    protected $newValue = null;
    
    /**
     * @var unknown
     */
    protected $oldValue = null;
    
    /**
     * @var unknown
     */
    public function setOldValue( $oldValue ) {
        $this->oldValue = $oldValue;
        return $this;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\Column
     */
    public function setValue( $value ) {
        $this->newValue = $value;
        return $this;
    }
    
    /**
     * Get value from Column object.
     * @return mixed
     */
    public function getValue() {
        $value = null;
        if ( null !== $this->newValue ) {
            $value = $this->newValue;
        } else if ( null !== $this->valueBuilder && is_callable($this->valueBuilder) ) {
            $value = call_user_func_array($this->valueBuilder, array($this->record, $this->getName()));
        } else {
            $value = $this->getDefault();
        }
        $this->newValue = $value;
        return $value;
    }
    
    /**
     * Get whether the value of Column has been modified.
     * @return boolean
     */
    public function getIsDirty() {
        return $this->oldValue !== $this->newValue;
    }
    
    /**
     * Clean the dirty status, set the value to new value.
     * @return Column
     */
    public function cleanDirty() {
        $this->newValue = $this->oldValue;
        return $this;
    }
    
    /**
     * This value contains all errors on current Column object. 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Add error to current Column object.
     * @param string $message
     * @return Column
     */
    public function addError( $message ) {
        $this->errors[] = call_user_func_array('sprintf', func_get_args());
        return $this;
    }
    
    /**
     * Get whether there is an error on current Column obejct.
     * @return boolean
     */
    public function hasError() {
        return 0 < count($this->errors);
    }
    
    /**
     * Get the errors on current Column object.
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * This value contains all custome validators for current Column object.
     * @var callback[]
     */
    protected $validators = array();
    
    /**
     * Add custome validator to current Column obejct.
     * @param callback $validator
     * @return Column
     */
    public function addValidator( $validator ) {
        $this->validators[] = $validator;
        return $this;
    }
    
    /**
     * Validate current Column object, and return validate result.
     * @return boolean
     */
    public function validate() {
        $this->errors = array();
        
        if ( $this->getIsAutoIncrement() ) {
            return true;
        }
        
        $value = $this->getValue();
        $isValidated = true;
        $isValidated = $isValidated && $this->validateNotNull($value);
        $isValidated = $isValidated && $this->validateDataType($value);
        $isValidated = $isValidated && $this->validateLength($value);
        $isValidated = $isValidated && $this->validateUnique($value);
        $isValidated = $isValidated && $this->validatePrimaryKey($value);
        $isValidated = $isValidated && $this->validateUnsigned($value);
        if ( false === $isValidated ){
            return false;
        }
        
        foreach ( $this->validators as $validator ) {
            call_user_func_array($validator, array($this->record, $this));
        }
        
        return !$this->hasError();
    }
    
    /**
     * "NOT NULL" check for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateNotNull( $value ) {
        if ( $this->getNullable() ) {
            return true;
        }
        
        if ( null === $value ) {
            $this->addError('"%s" can not be null.', $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data type for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataType( $value ) {
        $handler = 'validateDataType'.ucfirst(strtolower($this->getType()));
        if ( method_exists($this, $handler) ) {
            return call_user_func_array(array($this, $handler), array($value));
        } else {
            $this->addError('Unknown type "%s" of Column "%s".', $this->getType(), $this->getName());
            return false;
        }
    }
    
    /**
     * Validate the data type integer for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeInt( $value ) {
        if ( !empty($value) && (!is_numeric($value) || !is_int($value*1)) ) {
            $this->addError('The value of "%s" is not a validated integer.', $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * @param unknown $value
     * @return boolean
     */
    protected function validateDataTypeTinyint($value) {
        if (!empty($value)) {
            $isValidate = is_numeric($value);
            $isValidate = $isValidate && is_int($value*1);
            $isValidate = $isValidate && (($this->getIsUnsigned())?(0<$value&&$value<255):(-128<$value&&$value<128));
            if ( !$isValidate ){
                $this->addError('The value of "%s" is not a validated integer.', $this->getName());
                return false;
            }
        }
        return true;
    }
    
    /**
     * Validate the data type varchar for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeVarchar( $value ) {
        $validate = null===$value && $this->getNullable();
        $validate = $validate || is_numeric($value);
        $validate = $validate || is_string($value);
        $validate = $validate || (is_object($value) && method_exists($value, '__toString'));
        
        if ( !$validate ) {
            $this->addError('The value of "%s" is not a validated string.', $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * @param unknown $value
     * @return boolean
     */
    protected function validateDataTypeLongtext( $value ) {
        return $this->validateDataTypeVarchar($value);
    }
    
    /**
     * Validate the data type for datetime current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeDatetime( $value ) {
        if (!empty($value) && false === \DateTime::createFromFormat('Y-m-d H:i:s', $value) ) {
            $this->addError('The value of "%s" is not a validated date and time.', $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data type for datetime current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateDataTypeDate( $value ) {
        if ( !empty($value) && false === \DateTime::createFromFormat('Y-m-d', $value) ) {
            $this->addError('The value of "%s" is not a validated date and time.', $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data length for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateLength( $value ) {
        $type = strtoupper($this->getType());
        if ( 'VARCHAR' !== $type && 'CHAR' !== $type ) {
            return true;
        }
        
        $length = $this->getLength();
        $valLength = mb_strlen($value);
        if ( null !== $length && $valLength > $length ) {
            $this->addError('The value "%s" of "%s" is too long.', $value, $this->getName());
            return false;
        }
        
        $minLength = $this->getMinLength();
        if ( null !== $minLength && $valLength < $minLength ) {
            $this->addError('The value "%s" of "%s" is too short.', $value, $this->getName());
            return false;
        }
        
        return true;
    }
    
    /**
     * Unique check for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateUnique( $value ) {
        if ( !$this->record->getIsNew() && !$this->getIsDirty() ) {
            return true;
        }
        if( $this->getIsUnique() && $this->record->exists(array($this->getName()=>$value)) ) {
            $this->addError('The value "%s" for "%s" already exists.', $value, $this->getName());
            return false;
        }
        return true;
    }
    
    /**
     * Primary key check for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validatePrimaryKey( $value ) {
        if( $this->getIsPrimaryKey()
        &&  $this->getIsDirty() 
        &&  $this->record->exists(array($this->getName()=>$value)) ) {
            $this->addError('The key "%s" of "%s" already exists.', $value, get_class($this->record));
            return false;
        }
        
        return true;
    }
    
    /**
     * Unsigned check for current Column object.
     * @param mixed $value
     * @return boolean
     */
    protected function validateUnsigned( $value ){
        $unsignedTypes = array('TINYINT', 'SMALLINT','INT', 'BIGINT');
        $type = strtoupper($this->getType());
        if ( $this->getIsUnsigned() && in_array($type, $unsignedTypes) && 0>$value ) {
            $this->addError('The value "%s" of "%s" can not lower that 0.', $value, $this->getName());
            return false;
        }
        return true;
    }
}
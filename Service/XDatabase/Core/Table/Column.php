<?php
/**
 * This file defines the class of Column.
 */
namespace X\Service\XDatabase\Core\Table;
/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Service;
/**
 * 
 */
class Column {
    /**
     * Create a new column object.
     * 
     * @param string $name
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public static function setup( $name ) {
        $class = get_called_class();
        $column = new $class($name);
        return $column;
    }
    
    /**
     * Init the column.
     * 
     * @param string $name
     */
    public function __construct( $name ) {
        $this->set('name', $name);
    }
    
    /**
     * The attributes of a column.
     * 
     * @var array
     */
    protected $attributes = array(
        'name'              => null,
        'type'              => null,
        'length'            => null,
        'nullable'          => true,
        'default'           => null,
        'isAutoIncrement'   => false,
        'isZeroFill'        => false,
        'isUnsigned'        => false,
        'isBinary'          => false,
        'isPimaryKey'       => false,
    );
    
    /**
     * Set column's attribute.
     * @param string $name
     * @param mixed $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    protected function set( $name, $value ) {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    /**
     * Get the value of attribute.
     * @param unknown $name
     * @return mixed
     */
    protected function get( $name ) {
        return $this->attributes[$name];
    }
    
    /**
     * Set column type
     * @param string $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setType($value) {
        return $this->set('type', $value);
    }
    
    /**
     * Set length
     * @param integer $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setLength($value) {
        return $this->set('length', (int)$value);
    }
    
    /**
     * Set nullable
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setNullable($value) {
        return $this->set('nullable', $value?true:false);
    }
    
    /**
     * Set default value
     * @param mixed $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setDefault($value) {
        return $this->set('default', $value);
    }
    
    /**
     * Set is auto increment
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsAutoIncrement($value) {
        return $this->set('isAutoIncrement', $value);
    }
    
    /**
     * Set is zero fill
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsZeroFill($value) {
        return $this->set('isZeroFill', $value?true:false);
    }
    
    /**
     * Set is unsigned
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsUnsigned($value) {
        $this->set('isUnsigned', $value?true:false);
        return $this;
    }
    
    /**
     * Set is binary
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsBinary($value) {
        return $this->set('isBinary', $value?true:false);
    }
    
    /**
     * @param unknown $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsPrimaryKey( $value ) {
        return $this->set('isPimaryKey', $value?true:false);
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->get('name');
    }
    
    /**
     * @return string
     */
    public function getType() {
        return $this->get('type');
    }
    
    /**
     * @return integer
     */
    public function getLength() {
        return $this->get('length');
    }
    
    /**
     * @return boolean
     */
    public function getNullable() {
        return $this->get('nullable');
    }
    
    /**
     * @return mixed
     */
    public function getDefault() {
        return $this->get('default');
    }
    
    /**
     * @return boolean
     */
    public function getIsAutoIncrement() {
        return $this->get('isAutoIncrement');
    }
    
    /**
     * @return boolean
     */
    public function getIsZeroFill() {
        return $this->get('isZeroFill');
    }
    
    /**
     * @return boolean
     */
    public function getIsUnsigned() {
        return $this->get('isUnsigned');
    }
    
    /**
     * @return boolean
     */
    public function getIsBinary() {
        return $this->get('isBinary');
    }
    
    /**
     * @return boolean
     */
    public function getIsPrimaryKey() {
        return $this->get('isPimaryKey');
    }
    
    /**
     * Convert this column to description string.
     * @return string
     */
    public function toString() {
        $column = array();
        $column['type'] = $this->getType();
        if ( !is_null($this->getLength()) ) {
            $column['type'] .= '('.$this->getLength().')';
        }
        if ( $this->getIsZeroFill() ) {
            $column['isZeroFill'] = 'ZEROFILL';
        }
        if ( $this->getIsUnsigned() ) {
            $column['isUnsigned'] = 'UNSIGNED';
        }
        if ( $this->getIsBinary() ) {
            $column['isBinary'] = 'BINARY';
        }
        if ( $this->getIsAutoIncrement() ) {
            $column['isAutoIncrement'] = 'AUTO_INCREMENT';
        }
        if ( !$this->getNullable() ) {
            $column['nullable'] = 'NOT NULL';
        }
        if ( null !== $this->getDefault() ) {
            /* @var $service Service */
            $service = X::system()->getServiceManager()->get(Service::getServiceName());
            $db = $service->getDatabaseManager()->get();
            $value = $db->quote($this->getDefault());
            $column['default'] = 'DEFAULT '.$value;
        }
    
        $column = implode(' ', $column);
        return $column;
    }
}
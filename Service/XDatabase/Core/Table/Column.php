<?php
/**
 * This file defines the class of Column.
 */
namespace X\Service\XDatabase\Core\Table;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\Exception;

/**
 * 
 */
class Column extends Basic {
    /**
     * Create a new column object.
     * 
     * @param string $name
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public static function create( $name ) {
        $column = new Column($name);
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
     * 
     * @param string $name
     * @param mixed $value
     * @throws Exception
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    protected function set( $name, $value ) {
        if ( !array_key_exists($name, $this->attributes) ) {
            throw new Exception(sprintf('"%s" is not a validate attribute.', $name));
        }
        
        $this->attributes[$name] = $value;
        return $this;
    }
    
    /**
     * Get the value of attribute.
     * 
     * @param unknown $name
     * @throws Exception
     * @return mixed
     */
    protected function get( $name ) {
        if ( !array_key_exists($name, $this->attributes) ) {
            throw new Exception(sprintf('"%s" is not a validate attribute.', $name));
        }
        return $this->attributes[$name];
    }
    
    /**
     * Convert this column to description string.
     * @return string
     */
    public function toString() {
        $column = array();
        $column['type'] = $this->getType();
        if ( !is_null($this->getLength()) ) {
            $column['type'] .= sprintf('(%d)', $this->getLength());
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
        if ( !is_null($this->getDefault()) ) {
            $column['default'] = sprintf('DEFAULT "%s"', addslashes($this->getDefault()));
        }
        
        $column = implode(' ', $column);
        return $column;
    }
    
    /**
     * Set column name
     * @param string $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setName($value) {
        return $this->set('name', $value);
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
        return $this->set('length', $value);
    }
    
    /**
     * Set nullable
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setNullable($value) {
        return $this->set('nullable', $value);
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
        return $this->set('isZeroFill', $value);
    }
    
    /**
     * Set is unsigned
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsUnsigned($value) {
        return $this->set('isUnsigned', $value);
    }
    
    /**
     * Set is binary
     * @param boolean $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsBinary($value) {
        return $this->set('isBinary', $value);
    }
    
    /**
     * @param unknown $value
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function setIsPimaryKey( $value ) {
        return $this->set('isPimaryKey', $value);
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
    public function getIsPimaryKey() {
        return $this->get('isPimaryKey');
    }

}
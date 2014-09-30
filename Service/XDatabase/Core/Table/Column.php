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
 * The column class
 * 
 * @method string getName()
 * @method string getType()
 * @method string getLength()
 * @method string getNullable()
 * @method string getDefault()
 * @method string getIsAutoIncrement()
 * @method string getIsZeroFill()
 * @method string getIsUnsigned()
 * @method string getIsBinary()
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
    );
    
    /**
     * Set column's attribute.
     * 
     * @param string $name
     * @param mixed $value
     * @throws Exception
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function set( $name, $value ) {
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
    public function get( $name ) {
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
        if ( !$this->getNullable() ) {
            $column['nullable'] = 'NOT NULL';
        }
        if ( !is_null($this->getDefault()) ) {
            $column['default'] = sprintf('DEFAULT "%s"', $this->getDefault());
        }
        if ( $this->getIsAutoIncrement() ) {
            $column['isAutoIncrement'] = 'AUTO INCREMENT';
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
        
        $column = implode(' ', $column);
        return $column;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::__set()
     */
    public function __set($name, $value) {
        $this->set($name, $value);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Basic::__get()
     */
    public function __get($name) {
        $name = substr($name, 3);
        $name = lcfirst($name);
        return $this->get($name);
    }
    
    /**
     * 
     * @param unknown $name
     * @param unknown $parms
     */
    public function __call( $name, $parms ) {
        if ( 'get' === substr($name, 0, 3) ) {
            return $this->get(lcfirst(substr($name, 3)));
        }
    }
    
    /**
     * Convert this column to description string.
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
    
    /* column types */
    const T_INT         = 'INT';
    const T_TINYINT     = 'TINYINT';
    const T_SMALLINT    = 'SMALLINT';
    const T_MEDIUMINT   = 'MEDIUMINT';
    const T_BIGINT      = 'BIGINT';
    const T_FLOAT       = 'FLOAT';
    const T_DOUBLE      = 'DOUBLE';
    const T_DATE        = 'DATE';
    const T_TIME        = 'TIME';
    const T_YEAR        = 'YEAR';
    const T_DATETIME    = 'DATETIME';
    const T_TIMESTAMP   = 'TIMESTAMP';
    const T_CHAR        = 'CHAR';
    const T_VARCHAR     = 'VARCHAR';
    const T_TINYBLOB    = 'TINYBLOB';
    const T_TINYTEXT    = 'TINYTEXT';
    const T_BLOB        = 'BLOB';
    const T_TEXT        = 'TEXT';
    const T_MEDIUMBLOB  = 'MEDIUMBLOB';
    const T_MEDIUMTEXT  = 'MEDIUMTEXT';
    const T_LOGNGBLOB   = 'LOGNGBLOB';
    const T_LONGTEXT    = 'LONGTEXT';
    
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
     * Set column type as int
     * @param string $type
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function int( $type=self::T_INT ) {
        return $this->setType($type);
    }
    
    /**
     * Set column type as float
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function float() {
        return $this->setType(self::T_FLOAT);
    }
    
    /**
     * Set column type as double
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function double() {
        return $this->setType(self::T_DOUBLE);
    }
    
    /**
     * Set column type as date
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function date() {
        return $this->setType(self::T_DATE);
    }
    
    /**
     * Set column type as time
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function time() {
        return $this->setType(self::T_TIME);
    }
    
    /**
     * Set column type as year
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function year(){
        return $this->setType(self::T_YEAR);
    }
    
    /**
     * Set column type as datetime
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function datetime() {
        return $this->setType(self::T_DATETIME);
    }
    
    /**
     * Set column type as timestamp
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function timestamp() {
        return $this->setType(self::T_TIMESTAMP);
    }
    
    /**
     * Set column type as char
     * @param string $length
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function char($length) {
        $this->setLength($length);
        return $this->setType(self::T_CHAR);
    }
    
    /**
     * Set column type as varchar
     * @param string $length
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function varchar($length){
        $this->setLength($length);
        return $this->setType(self::T_VARCHAR);
    }
    
    /**
     * Set column type as blob
     * @param string $type
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function blob($type=self::T_BLOB) {
        return $this->setType($type);
    }
    
    /**
     * Set column type as text
     * @param string $type
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function text($type=self::T_TEXT) {
        return $this->setType($type);
    }
    
    /**
     * Set column is not nullable.
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function notNull() {
        return $this->setNullable(false);
    }
    
    /**
     * Set default value for column.
     * 
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function defaultVal( $val ) {
        return $this->setDefault($val);
    }
    
    /**
     * Set column as an auto increment column.
     * 
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function autoIncrement() {
        return $this->setIsAutoIncrement(true);
    }
    
    /**
     * Set column as zerofill.
     * 
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function zeroFill() {
        return $this->setIsZeroFill(true);
    }
    
    /**
     * Set column as unsigned
     * 
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function unsigned(){
        return $this->setIsUnsigned(true);
    }
    
    /**
     * Set column as binary.
     * 
     * @return \X\Service\XDatabase\Core\Table\Column
     */
    public function binary() {
        return $this->setIsBinary(true);
    }
}
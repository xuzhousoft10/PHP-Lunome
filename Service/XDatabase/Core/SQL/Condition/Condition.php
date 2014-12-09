<?php
/**
 * Namespace definatino
 */
namespace X\Service\XDatabase\Core\SQL\Condition;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\Exception;
use X\Service\XDatabase\Service as XDatabaseService;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression ;

/**
 * Condition
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Condition extends Basic {
    /**
     * This value contains all supported operators.
     * 
     * @var array
     */
    protected static $operators = array(
        '=', '<>', '>', '>=', '<', '<=', 'Like', 'StartWith', 
        'EndWith', 'Between', 'In', 'NotIn', 'Includes', 'Exists', 'NotExists');
    
    /**
     * The column name that current Condition object effected.
     * 
     * @var string
     */
    protected $column = null;
    
    /**
     * The operator of current Condition object.
     * 
     * @var integer
     */
    protected $operator = null;
    
    /**
     * The value that current Condition column would handle.
     * 
     * @var mixed
     */
    protected $value = null;
    
    /**
     * Initiate the Condition object by given informations
     * 
     * @param string $column The name of column
     * @param string $operator The operator of current Condition
     * @param string $value The value for column to handle
     * @return void
     */
    public function __construct( $column, $operator, $value ) {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }
    
    /**
     * Convert current Condition object to string.
     * 
     * @return string
     */
    public function toString() {
        $handler = sprintf('stringBuilder%s', self::$operators[$this->operator]);
        $condition = null;
        if ( method_exists($this, $handler) ) {
            $condition = $this->$handler();
        } else {
            $condition = $this->defaultStringBuilder();
        }
        return $condition;
    }
    
    /**
     * The default string build for curent Condition object.
     * 
     * @return string
     */
    protected function defaultStringBuilder() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue($this->value);
        $condition = sprintf('%s %s %s', $column, self::$operators[$this->operator], $value);
        return $condition;
    }
    
    /**
     * Build Condition string for operator "like" 
     * 
     * @return string
     */
    protected function stringBuilderLike() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue($this->value);
        return sprintf('%s LIKE %s', $column, $value);
    }
    
    /**
     * Build Condition string for operator "start with"
     * 
     * @return string
     */
    protected function stringBuilderStartWith() {
        $column = $this->getQuotedCurrentColumn();
        $value  = sprintf('%s', $this->quoteValue($this->value.'%%'));
        return sprintf('%s LIKE %s', $column, $value);
    }
    
    /**
     * Build Condition string for operator "end with"
     * 
     * @return string
     */
    protected function stringBuilderEndWith() {
        $column = $this->getQuotedCurrentColumn();
        $value  = sprintf('%s', $this->quoteValue('%%'.$this->value));
        return sprintf('%s LIKE %s', $column, $value);
    }
    
    /**
     * Build Condition string for operator "includes"
     *
     * @return string
     */
    protected function stringBuilderIncludes() {
        $column = $this->getQuotedCurrentColumn();
        $value  = sprintf('%s', $this->quoteValue('%%'.$this->value.'%%'));
        return sprintf('%s LIKE %s', $column, $value);
    }
    
    /**
     * Build Condition string for operator "between"
     * 
     * @return string
     */
    protected function stringBuilderBetween() {
        $column = $this->getQuotedCurrentColumn();
        $minVal = $this->quoteValue($this->value[0]);
        $maxVal = $this->quoteValue($this->value[1]);
        return sprintf('%s BETWEEN %s AND %s', $column, $minVal, $maxVal);
    }
    
    /**
     * Build Condition string for operator "in"
     * 
     * @return string
     */
    protected function stringBuilderIn() {
        $column = $this->getQuotedCurrentColumn();
        if ( is_array($this->value) ) {
            $values = array();
            foreach ( $this->value as $value ) {
                $values[] = $this->quoteValue($value);
            }
            $values = implode(',', $values);
        } else if (method_exists($this->value, 'toString')) {
            $values = $this->value->toString();
        } else {
            throw new Exception('Invalid value for in.');
        }
        
        $condition = sprintf('%s IN (%s)', $column, $values);
        return $condition;
    }
    
    /**
     * Build Condition string for operator "not in"
     *
     * @return string
     */
    protected function stringBuilderNotIn() {
        $column = $this->getQuotedCurrentColumn();
        $values = array();
        foreach ( $this->value as $value ) {
            $values[] = $this->quoteValue($value);
        }
        return sprintf('%s NOT IN (%s)', $column, implode(',', $values));
    }
    
    /**
     * 
     * @return string
     */
    protected function stringBuilderExists() {
        return sprintf('EXISTS ( %s )', $this->value);
    }
    
    protected function stringBuilderNotExists() {
        return sprintf('NOT EXISTS ( %s )', $this->value);
    }
    
    /**
     * Quote the string value
     * 
     * @param unknown $value
     * @return string
     */
    protected function quoteValue( $value ) {
        if ( $value instanceof SQLExpression ) {
            $value = $value->toString();
        } else {
            $value = $this->getDatabase()->quote($value);
        }
        return $value;
    }
    
    /**
     * @param unknown $name
     */
    private function quoteColumn( $name ) {
        return $this->getDatabase()->quoteColumnName($name);
    }
    
    /**
     * @return string
     */
    private function getQuotedCurrentColumn() {
        if ( false === strpos($this->column, '.') ) {
            return $this->quoteColumn($this->column);
        } else {
            $columnInfo = explode('.', $this->column);
            $tableName = $this->getDatabase()->quoteTableName($columnInfo[0]);
            $columnName = $this->quoteColumn($columnInfo[1]);
            return $tableName.'.'.$columnName;
        }
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database
     */
    private function getDatabase() {
        $dbService = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        return $dbService->getDatabase();
    }
    
    const OPERATOR_EQUAL            = 0;
    const OPERATOR_NOT_EQUAL        = 1;
    const OPERATOR_GREATER_THAN     = 2;
    const OPERATOR_GREATER_OR_EQUAL = 3;
    const OPERATOR_LESS_THAN        = 4;
    const OPERATOR_LESS_OR_EQUAL    = 5;
    const OPERATOR_LIKE             = 6;
    const OPERATOR_START_WITH       = 7;
    const OPERATOR_END_WITH         = 8;
    const OPERATOR_BETWEEN          = 9;
    const OPERATOR_IN               = 10;
    const OPERATOR_NOT_IN           = 11;
    const OPERATOR_INCLUDES         = 12;
    const OPERATOR_EXISTS           = 13;
    const OPERATOR_NOT_EXISTS       = 14;
}
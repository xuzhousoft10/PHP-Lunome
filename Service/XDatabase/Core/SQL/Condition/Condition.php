<?php
/**
 * Namespace definatino
 */
namespace X\Service\XDatabase\Core\SQL\Condition;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Util\Exception;
use X\Service\XDatabase\Core\SQL\Util\Expression;
use X\Service\XDatabase\Service as XDatabaseService;

/**
 * Condition
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Condition {
    /**
     * This value contains all supported operators.
     * @var array
     */
    protected static $operators = array(
        '=', '<>', '>', '>=', '<', '<=', 'Like', 'StartWith', 
        'EndWith', 'Between', 'In', 'NotIn', 'Includes', 'Exists', 'NotExists');
    
    /**
     * The column name that current Condition object effected.
     * @var string
     */
    protected $column = null;
    
    /**
     * The operator of current Condition object.
     * @var integer
     */
    protected $operator = null;
    
    /**
     * The value that current Condition column would handle.
     * @var mixed
     */
    protected $value = null;
    
    /**
     * Initiate the Condition object by given informations
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
     * @return string
     */
    public function toString() {
        $handler = 'stringBuilder'.self::$operators[$this->operator];
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
     * @return string
     */
    private function defaultStringBuilder() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue($this->value);
        $condition = $column.' '.self::$operators[$this->operator].' '.$value;
        return $condition;
    }
    
    /**
     * Build Condition string for operator "like" 
     * @return string
     */
    private function stringBuilderLike() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue($this->value);
        return $column.' LIKE '.$value;
    }
    
    /**
     * Build Condition string for operator "start with"
     * @return string
     */
    private function stringBuilderStartWith() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue($this->value.'%%');
        return $column.' LIKE '.$value;
    }
    
    /**
     * Build Condition string for operator "end with"
     * @return string
     */
    private function stringBuilderEndWith() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue('%%'.$this->value);
        return $column.' LIKE '.$value;
    }
    
    /**
     * Build Condition string for operator "includes"
     * @return string
     */
    private function stringBuilderIncludes() {
        $column = $this->getQuotedCurrentColumn();
        $value  = $this->quoteValue('%%'.$this->value.'%%');
        return $column.' LIKE '.$value;
    }
    
    /**
     * Build Condition string for operator "between"
     * @return string
     */
    private function stringBuilderBetween() {
        $column = $this->getQuotedCurrentColumn();
        if ( !is_array($this->value) || 2!==count($this->value) ) {
            throw new Exception('The value for between condition must be an array with two elements.');
        }
        $minVal = $this->quoteValue($this->value[0]);
        $maxVal = $this->quoteValue($this->value[1]);
        return $column.' BETWEEN '.$minVal.' AND '.$maxVal;
    }
    
    /**
     * @throws Exception
     * @return string
     */
    private function getValueStringForConditionIn() {
        if ( is_array($this->value) || $this->value instanceof \Iterator ) {
            $values = $this->value;
            if ( $values instanceof \Iterator ) {
                $values = iterator_to_array($this->value);
            }
            $values = array_map(array($this, 'quoteValue'), $values);
            $values = implode(',', $values);
        } else if (method_exists($this->value, 'toString')) {
            $values = $this->value->toString();
        } else {
            throw new Exception('The value for in codition is not validated.');
        }
        return $values;
    }
    
    /**
     * Build Condition string for operator "in"
     * @return string
     */
    private function stringBuilderIn() {
        $column = $this->getQuotedCurrentColumn();
        return $column.' IN ('.$this->getValueStringForConditionIn().')';
    }
    
    /**
     * Build Condition string for operator "not in"
     * @return string
     */
    private function stringBuilderNotIn() {
        $column = $this->getQuotedCurrentColumn();
        return $column.' NOT IN ('.$this->getValueStringForConditionIn().')';
    }
    
    /**
     * @return string
     */
    private function stringBuilderExists() {
        return 'EXISTS ( '.$this->value.' )';
    }
    
    /**
     * @return string
     */
    private function stringBuilderNotExists() {
        return 'NOT EXISTS ( '.$this->value.' )';
    }
    
    /**
     * Quote the string value
     * @param unknown $value
     * @return string
     */
    private function quoteValue( $value ) {
        if ( $value instanceof Expression ) {
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
        if ( false === strpos($name, '.') ) {
            return $this->getDatabase()->quoteColumnName($name);
        } else {
            $columnInfo = explode('.', $name);
            $tableName = $this->getDatabase()->quoteTableName($columnInfo[0]);
            $columnName = $this->quoteColumn($columnInfo[1]);
            return $tableName.'.'.$columnName;
        }
    }
    
    /**
     * @return string
     */
    private function getQuotedCurrentColumn() {
        return $this->quoteColumn($this->column);
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database
     */
    private function getDatabase() { 
        /* @var $dbService XDatabaseService */
        $dbService = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        return $dbService->getDatabaseManager()->get();
    }
    
    /**
     * @var unknown
     */
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
<?php
/**
 * Namespace definatino
 */
namespace X\Service\XDatabase\Core\SQL\Condition;

/**
 * SQLConditionBuilder
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Builder extends \X\Service\XDatabase\Core\Basic {
    /**
     * The style name of camel that table column named by.
     * 
     * @var integer
     */
    const NAME_STYLE_CAMEL = 1;
    
    /**
     * The style name of anake that table column named by.
     * 
     * @var integer
     */
    const NAME_STYLE_SNAKE = 2;
    
    /**
     * Name styles that column named by.
     * 
     * @default SQLConditionBuilder::NAME_STYLE_CAMEL
     * @var integer
     */
    protected static $nameStyle = self::NAME_STYLE_CAMEL;
    
    /**
     * Set the naming style of column.
     * 
     * @param integer $style -- SQLConditionBuilder::NAME_STYLE_*
     */
    public static function setNameStyle( $style ) {
        self::$nameStyle = $style;
    }
    
    /**
     * Build condition with magic method, The mgaic call format is "columnCodition"
     * It looks like this:
     * <pre>
     *      $Builder->columnAIs('valA');
     * </pre>
     * 
     * @param string $name The magic call name
     * @param array $parms The params to magic call
     * @return Builder
     */
    public function __call( $name, $parms ) {
        preg_match($this->getMagicCallMatchPattern(), $name, $matches);
        list( $name, $column, $handler ) = $matches;
        if ( self::NAME_STYLE_SNAKE == self::$nameStyle ) {
            $column = $this->convertNameToSnakeStyleFromCamelStyle($column);
        }
        $handler = lcfirst($handler);
        array_unshift($parms, $column);
        return call_user_func_array(array($this, $handler), $parms);
    }
    
    /**
     * Get the pattern for magic call.
     * 
     * @return string
     */
    protected function getMagicCallMatchPattern() {
        $methods = array(
                'Is',               'IsNot',        'Equals',
                'NotEquals',        'GreaterThan',  'GreaterOrEquals',
                'LessThan',         'LessOrEquals', 'Like',
                'StartWith',        'EndWith',      'Between',
                'In',               'NotIn');
        return sprintf('/^(.*?)(%s)$/', implode('|', $methods));
    }
    
    /**
     * Convert the column name to match table style
     * 
     * @param string $name The name of column
     * @return string
     */
    protected function convertNameToSnakeStyleFromCamelStyle( $name ) {
        $name = str_split($name);
        foreach ( $name as &$char ) {
            if ( ctype_upper($char) ) {
                $char = '_'.strtolower($char);
            }
        }
        return implode('', $name);
    }
    
    /**
     * Create a new condition Builder.
     * 
     * @param mixed $condition The initiate condtion for condition Builder
     * @return Builder
     */
    public static function build( $condition=null ) {
        $builder = new Builder();
        if ( !is_null($condition) ) {
            $builder->addCondition($condition);
        }
        return $builder;
    }
    
    /**
     * This value contains all the conditions
     * 
     * @var Condition[]
     */
    protected $conditions = array();
    
    /**
     * Add condition part to condition Builder. 
     * 
     * @param string|array $condition -- The condition to add.
     * @return Builder
     */
    public function addCondition( $condition ) {
        if ( is_array($condition) || ($condition instanceof \Iterator) ) {
            foreach ( $condition as $name => $value ) {
                if ( is_array($value) ||  ($value instanceof \Iterator) ) {
                    $con = new Condition($name, Condition::OPERATOR_IN, $value);
                }
                else {
                    $con = new Condition($name, Condition::OPERATOR_EQUAL, $value);
                }
                $this->addCondition($con);
            }
        }
        else if ( is_string($condition) 
            || $condition instanceof Condition 
            || $condition instanceof Builder ) {
            $this->conditions[] = $condition;
            $this->addConnector(Connector::CONNECTOR_AND);
        }
        
        return $this;
    }
    
    /**
     * Add a single condition into current condition query.
     * 
     * @param string $name The name of the column
     * @param string $operation The operator index of condition
     * @param mixed $value The value part of the condition.
     * @return Builder
     */
    public function addSigleCondition( $name, $operation, $value ) {
        $con = new Condition($name, $operation, $value);
        $this->addCondition($con);
        return $this;
    }
    
    /**
     * Add connector for condition.
     * 
     * @param integer $connector Connector::CONNECTOR_*
     * @return Builder
     */
    public function addConnector( $connector = Connector::CONNECTOR_AND ) {
        $connector = new Connector($connector);
        if ($this->conditions[count($this->conditions)-1] instanceof Connector ) {
            $this->conditions[count($this->conditions)-1] = $connector;
        }
        else {
            $this->conditions[] = $connector;
        }
        return $this;
    }
    
    /**
     * Add "is" condition into current condition group. 
     * 
     * @param string $name The name of column
     * @param mixed $value The value that column is.
     * @return Builder
     */
    public function is( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_EQUAL, $value);
        return $this;
    }
    
    /**
     * Add "is not" condition into current condition group. 
     * 
     * @param string $name The name of column.
     * @param mixed $value The value that column is not.
     * @return Builder
     */
    public function isNot( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_NOT_EQUAL, $value);
        return $this;
    }
    
    /**
     * Add "equals" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param mixed $value The value that column is
     * @return Builder
     */
    public function equals( $name, $value ) {
        return $this->is($name, $value);
    }
    
    /**
     * Add "not equals" condition into current condition group. 
     * 
     * @param string $name The name of column.
     * @param mixed $value The value that column not equals.
     * @return Builder
     */
    public function notEquals( $name, $value ) {
        return $this->isNot($name, $value);
    }
    
    /**
     * Add "greater than" condition into current condition group. 
     * 
     * @param string $name The name of the column.
     * @param mixed $value The value that column greater than.
     * @return Builder
     */
    public function greaterThan( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_GREATER_THAN, $value);
        return $this;
    }
    
    /**
     * Add "greater or equals" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param mixed $value The value that column greateer or equals
     * @return Builder
     */
    public function greaterOrEquals( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_GREATER_OR_EQUAL, $value);
        return $this;
    }
    
    /**
     * Add "less than" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param mixed $value The value of the column less than
     * @return Builder
     */
    public function lessThan( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_LESS_THAN, $value);
        return $this;
    }
    
    /**
     * Add "less or equals" condition into current condition group. 
     * 
     * @param string $name The name of the column 
     * @param mixed $value The value of the column less or equals.
     * @return Builder
     */
    public function lessOrEquals( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_LESS_OR_EQUAL, $value);
        return $this;
    }
    
    /**
     * Add "like" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param string $value The value that column likes
     * @return Builder
     */
    public function like( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_LIKE, $value);
        return $this;
    }
    
    /**
     * Add "start with" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param string $value The value that column start with
     * @return Builder
     */
    public function startWith( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_START_WITH, $value);
        return $this;
    }
    
    /**
     * Add "end with" condition into current condition group. 
     * 
     * @param string $name The name of column
     * @param string $value The value of column end with
     * @return Builder
     */
    public function endWith( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_END_WITH, $value);
        return $this;
    }
    
    /**
     * 
     * @param unknown $name
     * @param unknown $value
     * @return \X\Database\SQL\Condition\Builder
     */
    public function includes( $name, $value ) {
        $this->addSigleCondition($name, Condition::OPERATOR_INCLUDES, $value);
        return $this;
    }
    
    /**
     * Add "between" condition into current condition group. 
     * 
     * @param string $name The name of column
     * @param mixed $minValue The min value that column greater than
     * @param mixed $maxValue The max value that column less than
     * @return Builder
     */
    public function between( $name, $minValue, $maxValue ) {
        $this->addSigleCondition($name, Condition::OPERATOR_BETWEEN, array($minValue, $maxValue));
        return $this;
    }
    
    /**
     * Add "in" condition into current condition group. 
     * 
     * @param string $name The name of column
     * @param array $values The values that column could be.
     * @return Builder
     */
    public function in( $name, $values ) {
        $this->addSigleCondition($name, Condition::OPERATOR_IN, $values);
        return $this;
    }
    
    /**
     * Add "not in" condition into current condition group. 
     * 
     * @param string $name The name of the column
     * @param array $values The value that column could not be.
     * @return Builder
     */
    public function notIn( $name, $values ) {
        $this->addSigleCondition($name, Condition::OPERATOR_NOT_IN, $values);
        return $this;
    }
    
    /**
     * Add "and" connector into current condition group. 
     * 
     * @return Builder
     */
    public function andAlso() {
        $this->addConnector(Connector::CONNECTOR_AND);
        return $this;
    }
    
    /**
     * Add "or" connector into current condition group. 
     * 
     * @return Builder
     */
    public function orThat() {
        $this->addConnector(Connector::CONNECTOR_OR);
        return $this;
    }
    
    /**
     * Add group start into current condition group. 
     * 
     * @return Builder
     */
    public function groupStart() {
        $groupStart = new Group(Group::POSITION_START);
        $this->conditions[] = $groupStart;
        return $this;
    }
    
    /**
     * Add group end mark into current condition group. 
     * 
     * @return Builder
     */
    public function groupEnd() {
        $groupEnd = new Group(Group::POSITION_END);
        if ($this->conditions[count($this->conditions)-1] instanceof Connector ) {
            array_splice($this->conditions, count($this->conditions)-1, 1);
        }
        
        $this->conditions[] = $groupEnd;
        $this->addConnector(Connector::CONNECTOR_AND);
        return $this;
    }
    
    /**
     * Convert current condition group into string.
     * 
     * @return string
     */
    public function toString() {
        $conditions = array();
        foreach ( $this->conditions as $condition ) {
            $conditions[] = is_string($condition) ? $condition : $condition->toString();
        }
        array_pop($conditions);
        $condition = implode(' ', $conditions);
        return $condition;
    }
}
<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

use X\Service\XDatabase\Core\SQL\Func\XFunction;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
/**
 * Select
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Select extends ActionWithCondition {
    /**
     * Add action name into query.
     *
     * @return SQLBuilderActionSelect
     */
    protected function getActionNameString() {
        $this->sqlCommand[] = 'SELECT';
        return $this;
    }
    
    /**
     * The selected expressions
     * <pre>
     * -- key   : The alias of the expression, if the key is a number
     *            then, it means no alias.
     * -- value : The expression
     * </pre>
     * @var array
     */
    protected $expressions = array();
    
    /**
     * 增加表达式到选择的项目中。
     * @param mixed $expression 表达式
     * @param string $name 项目别名
     * @return \X\Service\XDatabase\Core\SQL\Action\Select
     */
    public function addExpression( $expression, $name=null ) {
        $this->expressions[] = array('expr'=>$expression, 'name'=>$name);
        return $this;
    }
    
    /**
     * Set selected columns which would appears in result.
     * <pre>
     * $Select->columns( 'col1','col2')->from('table');
     * $Select->columns( array('acol1'=>'col1','acol2'=>'col2') )->from('table');
     * </pre>
     * @param string $columns The columns to Select
     * @return Select
     */
    public function columns( $columns ) {
        if ( 1 == func_num_args() && is_array($columns) ) {
            foreach ( $columns as $alias => $expression ) {
                $this->addExpression($expression, $alias);
            }
        } else {
            foreach ( func_get_args() as $expression ) {
                $this->addExpression($expression);
            }
        }
        return $this;
    }
        
    /**
     * Do the same thing as columns method.
     * <pre>
     * $Select->expressions( new SQLFuncCount(), new SQLFuncMax() )
     * $Select->expressions( array('count'=>new SQLFuncCount(), 'max'=>new SQLFuncMax()) );
     * </pre>
     *  
     * @see Select::columns() Select::columns()
     * @param SQLFunction $expressions The expressions to Select query.
     * @return Select
     */
    public function expressions( $expressions ) {
        return call_user_func_array(array($this, 'columns'), func_get_args());
    }
    
    /**
     * Get Select expression part of command string.
     * This method is called by toString() method
     *
     * @return Select
     */
    protected function getExpressionString() {
        $expressions = array();
        foreach ( $this->expressions as $expression ) {
            if ( $expression['expr'] instanceof XFunction ) {
                $tempExpr = $expression['expr']->toString();
            } else if ( '*' === $expression['expr'] ) {
                $tempExpr = '*';
            } else {
                $column = explode('.', $expression['expr']);
                $column = $this->quoteColumnNames($column);
                $column = implode('.', $column);
                $tempExpr = sprintf('%s', $column);
            }
            if ( null !== $expression['name'] ) {
                $tempExpr = sprintf('%s AS %s', $tempExpr, $this->quoteColumnName($expression['name']));
            }
            $expressions[] = $tempExpr;
        }
        if ( 0 === count($expressions) ) {
            $expressions = array('*');
        }
        $this->sqlCommand[] = implode(',', $expressions);
        return $this;
    }
    
    /**
     * The table list that Select from.
     * <pre>
     * -- key   : The alias of the table. If the key is a 
     *              number, it means no alias for that table.
     * -- value : The name of the table.
     * </pre>
     * @var array
     */
    protected $tableReferences = array();
    
    /**
     * 
     * @param unknown $name
     * @param unknown $alias
     * @return \X\Service\XDatabase\Core\SQL\Action\Select
     */
    public function addTable( $name, $alias=null ) {
        if ( null === $alias ) {
            $this->from($name);
        } else {
            $this->from(array($alias=>$name));
        }
        return $this;
    }
    
    /**
     * Set from references.
     * <pre>
     * $Select->from('table1', 'table2');
     * $Select->from(array('tbl1'=>'table1', 'tbl2'=>'table2'));
     * </pre>
     * 
     * @param string $table The name of table to Select from.
     * @return Select
     */
    public function from( $table ) {
        $references = array();
        if ( 1 == func_num_args() && is_array($table) ) {
            $references = $table;
        }
        else {
            $references = func_get_args();
        }
        $this->tableReferences = array_merge($this->tableReferences, $references);
        return $this;
    }
    
    /**
     * Get from part of command string.
     * This method is called by toString() method. 
     *
     * @return Select
     */
    protected function getFromString() {
        if ( 0 == count($this->tableReferences)) return $this;
        
        $tables = array();
        foreach ( $this->tableReferences as $alias => $table ) {
            $reference = $this->quoteTableName($table);
            if ( !is_numeric($alias) ) {
                $reference = sprintf('%s AS %s', $reference, $this->quoteTableName($alias));
            }
            $tables[] = $reference;
        }
        $this->sqlCommand[] = sprintf('FROM %s', implode(',', $tables));
        return $this;
    }
    
    /**
     * The group definiation.
     * <pre>
     * --value : array('name'=>'GroupByName', 'order'=>'GroupOrder')
     * </pre>
     * 
     * @var array
     */
    protected $groups = array();
    
    /**
     * Set group to command.
     * <pre>
     * $Select->from('table')->groupBy('id');
     * $Select->from('table')->groupBy('id')->groupBy('name');
     * $Select->from('table')->groupBy(array('id', 'ASC'), array('id', 'ASC') );
     * </pre>
     * 
     * @param string $name The column name to group
     * @param string $order The order to that group
     * @return Select
    */
    public function groupBy( $name, $order=null ) {
        if ( is_array($name) ) {
            foreach ( func_get_args() as $group ) {
                call_user_func_array(array($this, 'groupBy'), $group);
            }
        }
        else {
            $group = array('name'=>$name, 'order'=>$order);
            $this->groups[] = $group;
        }
        return $this;
    }
    
    /**
     * Get group string of command.
     * This method is called by toString() method;
     * 
     * @return Select
     */
    protected function getGroupString() {
        if ( 0 == count($this->groups) ) return $this;
    
        $groups = array();
        foreach ( $this->groups as $group ) {
            $groupItem = $this->quoteColumnName($group['name']);
            if ( !is_null($group['order']) ) {
                $groupItem = sprintf('%s %s', $groupItem, $group['order']);
            }
            $groups[] = $groupItem;
        }
        $this->sqlCommand[] = sprintf('GROUP BY %s', implode(',', $groups));
        return $this;
    }
    
    /**
     * The having condition
     * 
     * @var string|SQLConditionBuilder
     */
    protected $havingCondition = null;
    
    /**
     * Set having condition to command.
     * <pre>
     * $Select->from('table')->groupBy('id')->having(SQLCondition $codition);
     * $Select->from('table')->groupBy('id')->having(array('col1'=>1, 'col2'=>2));
     * $Select->from('table')->groupBy('id')->having("col1=>1 && col2=>2");
     * </pre>
     * 
     * @param SQLConditionBuilder|string $condition The having condition to query
     * @return Select
     */
    public function having( $condition ) {
        $this->havingCondition = $condition;
        return $this;
    }
    
    /**
     * Get having condition part of command.
     * This method is called by toString() method.
     * 
     * @return Select
     */
    protected function getHavingString() {
        if ( empty($this->havingCondition) ) return $this;
    
        $condition = $this->havingCondition;
        if ( !($this->havingCondition instanceof ConditionBuilder ) ) {
            $condition = ConditionBuilder::build($this->havingCondition);
        }
        $condition = $condition->toString();
        $this->sqlCommand[] = sprintf('HAVING %s', $condition);
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'getActionNameString',
            'getExpressionString',
            'getFromString',
            'getConditionString',
            'getGroupString',
            'getOrderString',
            'getHavingString',
            'getLimitString',
            'getOffsetString',
        );
    }
}

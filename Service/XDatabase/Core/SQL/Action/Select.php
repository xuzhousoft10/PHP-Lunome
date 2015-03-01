<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;
/**
 * 
 */
use X\Service\XDatabase\Core\SQL\Util\Func;
use X\Service\XDatabase\Core\SQL\Util\ActionWithCondition;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
/**
 * Select
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Select extends ActionWithCondition {
    /**
     * @param mixed $expression
     * @param string $name
     * @return \X\Service\XDatabase\Core\SQL\Action\Select
     */
    public function expression( $expression, $name=null ) {
        $this->expressions[] = array('expr'=>$expression, 'name'=>$name);
        return $this;
    }
    
    /**
     * @param string $table The name of table to Select from.
     * @return Select
     */
    public function from( $table, $alias=null ) {
        $references = array('table'=>$table, 'alias'=>$alias);
        $this->tableReferences[] = $references;
        return $this;
    }
    
    /**
     * @param string $name The column name to group
     * @param string $order The order to that group
     * @return Select
     */
    public function groupBy( $name, $order=null ) {
        $group = array('name'=>$name, 'order'=>$order);
        $this->groups[] = $group;
        return $this;
    }
    
    /**
     * @param SQLConditionBuilder|string $condition The having condition to query
     * @return Select
     */
    public function having( $condition ) {
        $this->havingCondition = $condition;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'action','expression','table',
            'condition','group','order',
            'having','limit','offset');
    }
    
    /**
     * Add action name into query.
     * @return SQLBuilderActionSelect
     */
    protected function buildHandlerAction() {
        $this->sqlCommand[] = 'SELECT';
        return $this;
    }
    
    /**
     * @var array
     */
    protected $expressions = array();
    
    /**
     * Get Select expression part of command string.
     * This method is called by toString() method
     * @return Select
     */
    protected function buildHandlerExpression() {
        $expressions = array();
        foreach ( $this->expressions as $expression ) {
            if ( $expression['expr'] instanceof Func ) {
                $tempExpr = $expression['expr']->toString();
            } else if ( '*' === $expression['expr'] ) {
                $tempExpr = '*';
            } else {
                $tempExpr = $this->quoteColumnName($expression['expr']);
            }
            
            if ( null !== $expression['name'] ) {
                $tempExpr = $tempExpr.' AS '.$this->quoteColumnName($expression['name']);
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
     * @var array
     */
    protected $tableReferences = array();
    
    /**
     * Get from part of command string.
     * This method is called by toString() method. 
     * @return Select
     */
    protected function buildHandlerTable() {
        if ( 0 == count($this->tableReferences)) return $this;
        
        $tables = array();
        foreach ( $this->tableReferences as $item ) {
            $reference = $this->quoteTableName($item['table']);
            if ( isset($item['alias']) ) {
                $reference = $reference.' AS '.$this->quoteTableName($item['alias']);
            }
            $tables[] = $reference;
        }
        $this->sqlCommand[] = 'FROM '.implode(',', $tables);
        return $this;
    }
    
    /**
     * @var array
     */
    protected $groups = array();
    
    /**
     * @return Select
     */
    protected function buildHandlerGroup() {
        if ( 0 == count($this->groups) ) {
            return $this;
        }
        
        $groups = array();
        foreach ( $this->groups as $group ) {
            $groupItem = $this->quoteColumnName($group['name']);
            if ( null !== $group['order'] ) {
                $groupItem = $groupItem.' '.$group['order'];
            }
            $groups[] = $groupItem;
        }
        $this->sqlCommand[] = 'GROUP BY '.implode(',', $groups);
        return $this;
    }
    
    /**
     * @var string|SQLConditionBuilder
     */
    protected $havingCondition = null;
    
    /**
     * Get having condition part of command.
     * This method is called by toString() method.
     * @return Select
     */
    protected function buildHandlerHaving() {
        if ( empty($this->havingCondition) ) {
            return $this;
        }
        
        $condition = $this->havingCondition;
        if ( !($this->havingCondition instanceof ConditionBuilder ) ) {
            $condition = ConditionBuilder::build($this->havingCondition);
        }
        $condition = $condition->toString();
        $this->sqlCommand[] = 'HAVING '.$condition;
        return $this;
    }
}

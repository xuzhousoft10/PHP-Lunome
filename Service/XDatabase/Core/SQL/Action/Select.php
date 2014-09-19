<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

use X\Service\XDatabase\Core\SQL\Func\XFunction;
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
     * The name of fileter "ALL"
     * 
     * @var string
     */
    const FILTER_ALL            = 'ALL';
    
    /**
     * The name of fileter "DISTINCT"
     * 
     * @var string
     */
    const FILTER_DISTINCT       = 'DISTINCT';
    
    /**
     * The name of fileter "DISTINCTROW"
     * 
     * @var string
     */
    const FILTER_DISTINCTROW    = 'DISTINCTROW';
    
    /**
     * The way to filter results. 
     * The ALL and DISTINCT options specify whether duplicate 
     * rows should be returned.
     * 
     * @var string
     */
    protected $filter = null;
    
    /**
     * Set filters to Select query results.
     * <pre>
     * $Select->from('table')->filter(SQLBuilderActionSelect::DISTINCT);
     * </pre>
     * @param string $filter Select::FILTER_*
     * @return Select
     */
    public function filter( $filter ) {
        $this->filter = $filter;
        return $this;
    }
    
    /**
     * Add filter option into query.
     *
     * Get filter part of command stirng. 
     * This method is called by toStirng() method.
     *
     * @return Select
     */
    protected function getFilterString() {
        if ( is_null($this->filter) ) return $this;
        $this->sqlCommand[] = $this->filter;
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
    protected $selectExpr = array('*');
    
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
            $this->selectExpr = $columns;
        }
        else {
            $this->selectExpr = func_get_args();
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
    protected function getSelectExprString() {
        $exprs = array();
        foreach ( $this->selectExpr as $alias => $expr ) {
            if ( $expr instanceof XFunction ) {
                $expression = $expr->toString();
            }
            else if ( '*' === $expr ) {
                $expression = sprintf('%s', $expr);
            }
            else {
                $expression = sprintf('`%s`', $expr);
            }
            if ( !is_numeric($alias) ) {
                $expression = sprintf('%s AS `%s`', $expression, $alias);
            }
            $exprs[] = $expression;
        }
        $this->sqlCommand[] = implode(',', $exprs);
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
            $reference = sprintf('`%s`', $table);
            if ( !is_numeric($alias) ) {
                $reference = sprintf('%s AS `%s`', $reference, $alias);
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
            $groupItem = sprintf('`%s`', $group['name']);
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
        if ( !($this->havingCondition instanceof \X\Database\SQL\Condition\Builder ) ) {
            $condition = \X\Database\SQL\Condition\Builder::build($this->havingCondition);
        }
        $condition = $condition->toString();
        $this->sqlCommand[] = sprintf('HAVING %s', $condition);
        return $this;
    }
    
    /**
     * The options of into command.
     * 
     * @var array
     */
    protected $selectIntoOptions = array('handler'=>null, 'options'=>array());
    
    /**
     * Set out file to command
     * <pre>
     * $Select->from('table')->intoOutFile('1.txt', $options);
     * </pre>
     * @param string $path The path of file to save result.
     * @param string $option The option for Select query.
     * @return Select
     */
    public function intoOutFile( $path, $option=array() ) {
        $this->selectIntoOptions['handler'] = 'OutFile';
        $this->selectIntoOptions['options']['path'] = $path;
        $this->selectIntoOptions['options']['option'] = $option;
        return $this;
    }
    
    /**
     * Set dump file to command.
     * <pre>
     * $Select->from('table')->intoDumpFile( '1.txt' )
     * </pre>
     * 
     * @param string $path The path where dump file saved.
     * @return Select
     */
    public function intoDumpFile( $path ) {
        $this->selectIntoOptions['handler'] = 'DumpFile';
        $this->selectIntoOptions['options']['path'] = $path;
        return $this;
    }
    
    /**
     * Set the value names the Select into.
     * <pre>
     * $Select->from('table')->intoVar('varname1', 'varname2');
     * </pre>
     * 
     * @param string $var The name of value.
     * @param string $_ 
     * @return Select
     */
    public function intoVar( $var ) {
        $this->selectIntoOptions['handler'] = 'Var';
        $this->selectIntoOptions['options']['vars'] = func_get_args();
        return $this;
    }
    
    /**
     * Get into string of command. 
     * This method is called by toString() method.
     * 
     * @return Select
     */
    protected function getIntoString() {
        if ( is_null($this->selectIntoOptions['handler']) ) return $this;
        
        $handler = sprintf('getIntoString%s', $this->selectIntoOptions['handler']);
        $this->$handler();
        return $this;
    }
    
    /**
     * getIntoStringOutFile
     * 
     * @return Select
     */
    protected function getIntoStringOutFile() {
        $file = $this->selectIntoOptions['options']['path'];
        $file = \X\Database\Management::getManager()->getDb()->quote($file);
        $options = $this->selectIntoOptions['options']['option'];
        $sql = sprintf('INTO OUTFILE %s', $file);
        if ( 0 < count($options) ) {
            $sql = sprintf('%s %s', $sql, implode(',', $options));
        }
        
        $this->sqlCommand[] = $sql;
        return $this;
    }
    
    /**
     * getIntoStringDumpFile
     * 
     * @return Select
     */
    protected function getIntoStringDumpFile() {
        $file = $this->selectIntoOptions['options']['path'];
        $file = \X\Database\Management::getManager()->getDb()->quote($file);
        $this->sqlCommand[] = sprintf('INTO DUMPFILE %s', $file);
        return $this;
    }
    
    /**
     * getIntoStringVar
     *
     * @return Select
     */
    protected function getIntoStringVar() {
        $vars = $this->selectIntoOptions['options']['vars'];
        $this->sqlCommand[] = sprintf('INTO %s', implode(',', $vars));
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
            'getActionNameString',
            'getFilterString',
            'getSelectExprString',
            'getFromString',
            'getConditionString',
            'getOrderString',
            'getGroupString',
            'getHavingString',
            'getLimitString',
            'getOffsetString',
            'getIntoString'
        );
    }
}

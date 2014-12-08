<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * 
 */
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Action\Basic;
use X\Service\XDatabase\Core\SQL\Func\XFunction;

/**
 * ActionWithCondition
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class ActionWithCondition extends Basic {
    /**
     * This value contains the condition part of query.
     *
     * @var string|\X\Database\SQL\Condition\Builder
     */
    protected $condition = null;
    
    /**
     * Add where condition to query command.
     * Here are some example here:
     * <pre>
     * $select->from('table')->where( SQLCondition $condition);
     * $select->from('table')->where( array('col1'=>1, 'col2'=>2) );
     * $select->from('table')->where( "col1=>1 && col2=>2" );
     * </pre>
     * 
     * @param \X\Database\SQL\Condition\Builder|string $condition 
     *          The condition to add into the query.
     * @return ActionWithCondition
     */
    public function where( $condition ) {
        $this->condition = $condition;
        return $this;
    }
    
    /**
     * Get condition string for query command.
     * This method is called by toString() method.
     *
     * @return ActionWithCondition
     */
    protected function getConditionString() {
        if ( empty($this->condition) ) return $this;
    
        $condition = $this->condition;
        if ( !($this->condition instanceof ConditionBuilder ) ) {
            $condition = ConditionBuilder::build($this->condition);
        }
        $condition = $condition->toString();
        if ( !empty($condition) ) {
            $this->sqlCommand[] = sprintf('WHERE %s', $condition);
        }
        return $this;
    }
    
    /**
     * The orders for select command.
     * Here is an example about this value :
     * <pre>
     *  array(
     *      array(
     *          'expr'  => 'Order Name',
     *          'order' => 'Order Name',
     *          ...
     *      )
     *  );
     * </pre>
     *
     * @var array
     */
    protected $orders = array();
    
    /**
     * Add order to query command.
     * Here are some examples :
     * <pre>
     * $select->from('table')->orderBy('id')->orderBy('age', 'ASC');
     * $select->from('table')->orderBy( new SQLFuncRand() );
     * $select->from('table')->orderby(array(array('id'),array('age', 'ACS),array(new SQLFuncRand()) ))
     * </pre>
     * 
     * @param string $name The name of column to ordered.
     * @param string $order The order for that column
     * @return ActionWithCondition
    */
    public function orderBy( $name, $order=null ) {
        if ( is_array($name) ) {
            foreach ( func_get_args() as $order ) {
                call_user_func_array(array($this, 'orderBy'), $order);
            }
        }
        else {
            $order = array('expr'=>$name, 'order'=>$order);
            $this->orders[] = $order;
        }
        return $this;
    }
    
    /**
     * 
     * @param string $orders
     * @return \X\Database\SQL\Action\ActionWithCondition
     */
    public function orders( $orders=null ) {
        if ( is_null($orders) ) {
            return $this;
        }
        foreach ( $orders as $name => $order ) {
            if ( is_numeric($name) ) {
                $this->orderBy($order);
            } else {
                $this->orderBy($name, $order);
            }
        }
        return $this;
    }
    
    /**
     * Check whether there is any order on query command.
     * 
     * @return boolean
     */
    protected function hasOrder() {
        return 0 < count($this->orders);
    }
    
    /**
     * Get order command part.
     * This method is called by toString() method.
     *
     * @return ActionWithCondition
     */
    protected function getOrderString() {
        if ( !$this->hasOrder() ) return $this;
    
        $orders = array();
        foreach ( $this->orders as $order ) {
            $expr = $order['expr'];
            if ( $expr instanceof XFunction ) {
                $expr = $expr->toString();
            }
            else {
                $expr = $this->quoteTableName($expr);
            }
            $orders[] = sprintf('%s %s', $expr, is_null($order['order']) ? '' : $order['order']);
        }
        $this->sqlCommand[] = sprintf('ORDER BY %s', implode(',', $orders));
        return $this;
    }
    
    /**
     * The limitation of effected row.
     *
     * @var integer
     */
    protected $limit = null;
    
    /**
     * Set the limitation to command.
     * Here is an example :
     * <pre>
     * $select->from('table')->limit(1);
     * </pre>
     * 
     * @param integer $limit The limitation to command
     * @return ActionWithCondition
     */
    public function limit( $limit ) {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Get limitation part of command stirng.
     * This method is called by toString() method.
     *
     * @return ActionWithCondition
     */
    protected function getLimitString() {
        if ( is_null($this->limit) || 0 == $this->limit ) return $this;
        $this->sqlCommand[] = sprintf('LIMIT %s', $this->limit);
        return $this;
    }
    
    /**
     * The offset that effected record start from.
     *
     * @var integer
     */
    protected $offset = null;
    
    /**
     * Set the offset to command.
     * <pre>
     * $select->from('table')->offset(1);
     * </pre>
     * @param integer $offset The offset that effect record start from.
     * @return ActionWithCondition
     */
    public function offset( $offset ) {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Get offset part of command.
     * This method is called by toString() method.
     *
     * @return SQLBuilderActionSelect
     */
    protected function getOffsetString() {
        if ( is_null($this->offset) || 0 == $this->offset ) return $this;
        $this->sqlCommand[] = sprintf('OFFSET %s', $this->offset);
        return $this;
    }
}
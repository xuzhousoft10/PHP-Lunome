<?php
namespace X\Service\XDatabase\Core\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
/**
 * 
 */
class Query {
    /**
     * @var array
     */
    protected $tables = null;
    
    /**
     * Set the target tables
     * @param array $tables The list of tables
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function addTable( $name, $alias=null ) {
        $this->tables[] = array('name'=>$name, 'alias'=>$alias);
        return $this;
    }
    
    /**
     * The active column list, default to all
     * @var array
     */
    protected $expressions = array();
    
    /**
     * Set Active column for query.
     * @param array $columns
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function addExpression( $name, $alias=null ) {
        $this->expressions[] = array('expression'=>$name, 'alias'=>$alias);
        return $this;
    }
    
    /**
     * The condition for sub query.
     * @var mixed
     */
    protected $condition = null;
    
    /**
     * Set condition to find one record.
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function find( $condition=null ) {
        $this->condition = $condition;
        $this->limit = 1;
        return $this;
    }
    
    /**
     * Set condition to find all records.
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function findAll( $condition=null ) {
        $this->condition = $condition;
        $this->limit = 0;
        return $this;
    }
    
    /**
     * The limitiation of result.
     * @var integer
     */
    protected $limit = 1;
    
    /**
     * Set the limitation to result.
     * @param integer $limit
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function setLimit( $limit ) {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * @var unknown
     */
    protected $offset = 0;
    
    /**
     * @param unknown $offset
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function setOffset( $offset ) {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * @var unknown
     */
    protected $orders = array();
    
    /**
     * @param unknown $orders
     * @return \X\Service\XDatabase\Core\ActiveRecord\Query
     */
    public function addOrder( $name, $order=null ) {
        $this->orders[] = array('name'=>$name, 'order'=>$order);
        return $this;
    }
    
    /**
     * Get the query string
     * @return string
     */
    public function toString() {
        $sql = SQLBuilder::build()->select()
            ->where($this->condition)
            ->limit($this->limit)
            ->offset($this->offset);
        
        foreach ( $this->tables as $table ) {
            $sql->from($table['name'], $table['alias']);
        }
        foreach ( $this->orders as $order ) {
            $sql->orderBy($order['name'], $order['order']);
        }
        foreach ( $this->expressions as $expression ) {
            $sql->expression($expression['expression'], $expression['alias']);
        }
        return $sql->toString();
    }
}
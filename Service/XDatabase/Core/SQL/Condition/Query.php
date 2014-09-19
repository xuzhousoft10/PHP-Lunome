<?php
namespace X\Service\XDatabase\Core\SQL\Condition;
use X\Service\XDatabase\Core\Basic;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
class Query extends Basic {
    /**
     * 
     * @var array
     */
    protected $tables = null;
    
    /**
     * Set the target tables
     * 
     * @param array $tables The list of tables
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function setTables( array $tables ) {
        $this->tables = $tables;
        return $this;
    }
    
    /**
     * The active column list, default to all
     *
     * @var array
     */
    protected $columns = array('*');
    
    /**
     * Set Active column for query.
     *
     * @param array $columns
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
    */
    public function activeColumns( array $columns = array('*') ) {
        $this->columns = $columns;
        return $this;
    }
    
    /**
     * The condition for sub query.
     * 
     * @var mixed
     */
    protected $condition = null;
    
    /**
     * Set condition to find one record.
     * 
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function find( $condition=null ) {
        $this->condition = $condition;
        $this->limit = 1;
        return $this;
    }
    
    /**
     * Set condition to find all records.
     * 
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function findAll( $condition=null ) {
        $this->condition = $condition;
        $this->limit = 0;
        return $this;
    }
    
    /**
     * The limitiation of result.
     * 
     * @var integer
     */
    protected $limit = 1;
    
    /**
     * Set the limitation to result.
     * 
     * @param integer $limit
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function setLimit( $limit ) {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * 
     * @var unknown
     */
    protected $offset = 0;
    
    /**
     * 
     * @param unknown $offset
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function setOffset( $offset ) {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * 
     * @var unknown
     */
    protected $orders = array();
    
    /**
     * 
     * @param unknown $orders
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function setOrders( $orders ) {
        $this->orders = $orders;
        return $this;
    }
    
    /**
     * 
     * @var unknown
     */
    protected $position = 0;
    
    /**
     * 
     * @param unknown $position
     * @return \X\Service\XDatabase\Core\SQL\Condition\Query
     */
    public function setPosition( $position ) {
        $this->position = $position;
        return $this;
    }
    /**
     * Get the query string
     * 
     * @return string
     */
    public function toString() {
        $sql = SQLBuilder::build()->select()
            ->columns($this->columns)
            ->from($this->tables)
            ->where($this->condition)
            ->limit($this->limit)
            ->offset($this->position)
            ->orders($this->orders)
            ->toString();
        return $sql;
    }
}
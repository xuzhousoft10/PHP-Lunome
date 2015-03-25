<?php
/**
 *
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

/**
 * 
 * @author michael
 *
 */
class Criteria {
    /**
     * The condition for current query.
     * @var mixed
     */
    public $condition = null;
    
    /**
     * @var array
     */
    private $orders = array();
    
    /**
     * @param mixed $expression
     * @param string $order
     */
    public function addOrder( $expression, $order=null ) {
        $this->orders[] = array('expression'=>$expression, 'order'=>$order);
    }
    
    /**
     * @return boolean
     */
    public function hasOrder() {
        return !empty($this->orders);
    }
    
    /**
     * @return array
     */
    public function getOrders() {
        return $this->orders;
    }
    
    /**
     * @var integer
     */
    public $limit = 0;
    
    /**
     * @var integer
     */
    public $position = 0;
}
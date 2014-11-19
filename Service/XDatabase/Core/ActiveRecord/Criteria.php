<?php
/**
 *
 */
namespace X\Service\XDatabase\Core\ActiveRecord;

/**
 * 
 */
use X\Service\XDatabase\Core\Basic;

/**
 * 
 * @author michael
 *
 */
class Criteria extends Basic {
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
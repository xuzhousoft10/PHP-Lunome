<?php
/**
 * Group.php
 */
namespace X\Service\XDatabase\Core\SQL\Condition;
use X\Service\XDatabase\Core\Basic;
/**
 * Group
 * 
 * @author  Michael Luthor <michael.the.ranidae@gamil.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Group extends Basic {
    /**
     * The start Group mark
     * 
     * @var integer
     */
    const POSITION_START = 1;
    
    /**
     * The end mark of Group
     * 
     * @var integer
     */
    const POSITION_END = 2;
    
    /**
     * The postions mark of the Group
     * 
     * @var integer
     */
    protected $position = null;
    
    /**
     * Initiate the Group object
     * 
     * @param integer $position The position of Group mark
     * @return void
     */
    public function __construct( $position ) {
        $this->position = $position;
    }
    
    /**
     * Convert current Group object to string
     * 
     * @return string
     */
    public function toString() {
        return $this->position == self::POSITION_START ? '(' : ')';
    }
}
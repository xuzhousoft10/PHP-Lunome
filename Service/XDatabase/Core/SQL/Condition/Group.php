<?php
/**
 * Group.php
 */
namespace X\Service\XDatabase\Core\SQL\Condition;

/**
 * Group
 * 
 * @author  Michael Luthor <michael.the.ranidae@gamil.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Group {
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
    protected function __construct( $position ) {
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
    
    /**
     * @return \X\Service\XDatabase\Core\SQL\Condition\Group
     */
    public static function start() {
        return new Group(self::POSITION_START);
    }
    
    /**
     * @return \X\Service\XDatabase\Core\SQL\Condition\Group
     */
    public static function end() {
        return new Group(self::POSITION_END);
    }
}
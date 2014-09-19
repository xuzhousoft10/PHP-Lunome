<?php
/**
 * action.about.table.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * ActionAboutTable
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class ActionAboutTable extends Basic {
    /**
     * The name of table to operate.
     *
     * @var string
     */
    protected $name = null;
    
    /**
     * Set the name of the table to operate
     *
     * @param string $name The name for table to rename
     * @return ActionAboutTable
     */
    public function name( $name ) {
        $this->name = $name;
        return $this;
    }
}
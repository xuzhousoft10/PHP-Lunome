<?php
/**
 * rename.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * 
 */
use X\Service\XDatabase\Core\Exception;

/**
 * Rename
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Rename extends ActionAboutTable {
    /**
     * The new name of the table.
     * 
     * @var string
     */
    protected $newName = null;
    
    /**
     * Set new name for table
     * 
     * @param string $name The new name of the table.
     * @return Rename
     */
    public function newName( $name ) {
        $this->newName = $name;
        return $this;
    }
    
    /**
     * Add command into query.
     * 
     * @return Rename
     */
    protected function getNameString() {
        if ( is_null($this->name) ) {
            throw new Exception('Name can not be empty to rename the table.');
        }
        if ( is_null($this->newName) ) {
            throw new Exception('New name can not be empty to the table.');
        }
        $this->sqlCommand[] = sprintf('RENAME TABLE %s TO %s', $this->quoteColumnName($this->name), $this->quoteColumnName($this->newName));
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array('getNameString');
    }
}
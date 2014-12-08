<?php
/**
 * truncate.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * 
 */
use X\Service\XDatabase\Core\Exception;

/**
 * Truncate
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Truncate extends ActionAboutTable {
    /**
     * Add action to query.
     * 
     * @return Truncate
     */
    protected function getNameString() {
        if ( empty($this->name) ) {
            throw new Exception("Name can not be empty to truncate the table.");
        }
        $this->sqlCommand[] = sprintf('TRUNCATE TABLE %s', $this->quoteTableName($this->name));
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
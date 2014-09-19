<?php
/**
 * drop.table.php
 */
namespace X\Service\XDB\SQL\Action;

/**
 * DropTable
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class DropTable extends ActionAboutTable {
    /**
     * Add action name into query.
     * 
     * @return DropTable
     */
    protected function getNameString() {
        if ( is_null($this->name) ) {
            throw new \X\Database\Exception(sprintf('Name can not be empty to delete the table.', $this->name));
        }
        
        $this->sqlCommand[] = sprintf('DROP TABLE %s', $this->quoteColumnName($this->name));
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() parent::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array('getNameString');
    }
}
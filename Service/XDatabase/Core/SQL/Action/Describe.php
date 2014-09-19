<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Update
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   0.0.0
 */
class Describe extends Basic {
    /**
     * Add action name into query
     *
     * @return Update
     */
    protected function getActionNameString() {
        $this->sqlCommand[] = 'DESCRIBE';
        return $this;
    }
    

    /**
     * The table reference.
     *
     * @var string
     */
    protected $tableReference = '';
    
    /**
     * Set Table to Update
     *
     * @param string $table The table's name to Update
     * @return Update
     */
    public function table( $table ) {
        $this->tableReference = $table;
        return $this;
    }
    
    /**
     * Add table string into query.
     *
     * @return Update
     */
    protected function getTableString() {
        $this->sqlCommand[] = sprintf('`%s`', $this->tableReference);
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Database\SQL\Action\Base::getBuildHandlers() Base::getBuildHandlers()
     */
    protected function getBuildHandlers() {
        return array(
                'getActionNameString',
                'getTableString',
        );
    }
}
<?php
/**
 * Select.php
 */
namespace X\Service\XDatabase\Core\SQL\Action;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Service as XDatabaseService;

/**
 * Base
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class Basic extends \X\Service\XDatabase\Core\Basic {
    /**
     * This value contains each part of query string.
     * 
     * @var string
     */
    protected $sqlCommand = array();
    
    /**
     * Get the handlers array, each element is the method name
     * of the subclass class 
     * 
     * @return string[]
     */
    abstract protected function getBuildHandlers();
    
    /**
     * Convert this action into sql command string.
     *
     * @return string
     */
    public function toString() {
        foreach ( $this->getBuildHandlers() as $handler ) {
            if ( method_exists($this, $handler) ) {
                call_user_func_array(array($this, $handler), array());
            } else {
                $this->sqlCommand[] = $handler;
            }
        }
        
        return implode(' ', $this->sqlCommand);
    }
    
    /**
     * Quote the column's name for safty use in query string.
     * 
     * @param string $name The name of column to quote.
     * @return string
     */
    protected function quoteColumnName( $name ) {
        return sprintf('`%s`', $name);
    }
    
    /**
     * Quote the columns' name for safty use in query string.
     * 
     * @param array $names The column name list to quote.
     * @return array
     */
    protected function quoteColumnNames( $names ) {
        return array_map(array($this, 'quoteColumnName'), $names);
    }
    
    /**
     * Quote the table name for safty use in query string.
     * 
     * @param string $name The name of table to quote.
     * @return string
     */
    protected function quoteTableName( $name ) {
        return sprintf('`%s`', $name);
    }
    
    /**
     * Quote the string value
     *
     * @param unknown $value
     * @return string
     */
    protected function quoteValue( $value ) {
        $dbService = X::system()->getServiceManager()->get(XDatabaseService::SERVICE_NAME);
        return $dbService->getDb()->quote($value);
    }
}
<?php
/**
 * count.php
 */
namespace X\Service\XDatabase\Core\SQL\Util;
/**
 * 
 */
use X\Core\X;
use X\Service\XDatabase\Service as XDatabaseService;
/**
 * Func
 * Abstract class for sql functions.
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
abstract class Func {
    /**
     * 
     */
    abstract public function toString();
    
    /**
     * @param unknown $name
     */
    protected function quoteColumnName( $name ) {
        /* @var $service XDatabaseService */
        $service = X::system()->getServiceManager()->get(XDatabaseService::getServiceName());
        $database = $service->getDatabaseManager()->get();
        
        $column = explode('.', $name);
        $column = array_map(array($database, 'quoteColumnName'), $column);
        $column = implode('.', $column);
        return $column;
    }
}
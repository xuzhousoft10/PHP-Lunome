<?php
/**
 * Namespace definatino
 */
namespace X\Service\XDatabase\Core\SQL;

/**
 * 
 */
use X\Service\XDatabase\Core\Basic;

/**
 * SQLBuilder
 * 
 * @author  Michael Luthor <Michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Builder extends Basic {
    /**
     * Get a new SQLBuilder
     * 
     * @return SQLBuilder
     */
    public static function build() {
        return new Builder();
    }
    
    /**
     * Build action object by given name
     * 
     * @param string $name The name of action.
     * @return \X\Database\SQL\Action\Base
     */
    protected function getActionByName( $name ) {
        $action = sprintf('%s\\Action\\%s', __NAMESPACE__, $name);
        return new $action();
    }
    
    /**
     * 
     * @return \X\Service\XDatabase\Core\SQL\Action\Describe
     */
    public function describe() {
        return $this->getActionByName('Describe');
    }
    
    /**
     * Get select action object.
     * 
     * @return \X\Service\XDatabase\Core\SQL\Action\Select
     */
    public function select() {
        return $this->getActionByName('Select');
    }
    
    /**
     * Get insert action object.
     * 
     * @return \X\Database\SQL\Action\Insert
     */
    public function insert() {
        return $this->getActionByName('Insert');
    }
    
    /**
     * Get update action object.
     * 
     * @return \X\Database\SQL\Action\Update
     */
    public function update() {
        return $this->getActionByName('Update');
    }
    
    /**
     * Get delete action object.
     * 
     * @return \X\Database\SQL\Action\Delete
     */
    public function delete() {
        return $this->getActionByName('Delete');
    }
    
    /**
     * Get create table action object.
     * 
     * @return \X\Database\SQL\Action\CreateTable
     */
    public function createTable() {
        return $this->getActionByName('CreateTable');
    }
    
    /**
     * Get drop table action object.
     * 
     * @return \X\Database\SQL\Action\DropTable
     */
    public function dropTable() {
        return $this->getActionByName('DropTable');
    }
    
    /**
     * Get truncate action object.
     * 
     * @return \X\Database\SQL\Action\Truncate
     */
    public function truncate() {
        return $this->getActionByName('Truncate');
    }
    
    /**
     * Get rename action object.
     * 
     * @return \X\Database\SQL\Action\Rename
     */
    public function rename() {
        return $this->getActionByName('Rename');
    }
    
    /**
     * Get alter table action object.
     * 
     * @return \X\Database\SQL\Action\AlterTable
     */
    public function alterTable() {
        return $this->getActionByName('AlterTable');
    }
}
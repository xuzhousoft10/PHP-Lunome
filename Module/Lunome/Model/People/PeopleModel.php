<?php
/**
 * 
 */
namespace X\Module\Lunome\Model\People;

/**
 * 
 */
use X\Util\Model\Basic;

/*
 CREATE  TABLE `lunome`.`people` (
  `id` VARCHAR(36) NOT NULL ,
  `name` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) );
 */

/**
 * @property string $id
 * @property string $name
 **/
class PeopleModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']            = 'VARCHAR(128) NOTNULL';
        return $columns;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'people';
    }
}
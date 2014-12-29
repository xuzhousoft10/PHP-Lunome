<?php
namespace X\Module\Lunome\Model\Account;

/**
 * 
 */
use X\Util\Model\Basic;
/**
 * 
  CREATE  TABLE `lunome`.`account_information` (
  `id` VARCHAR(36) NOT NULL ,
  `account_id` VARCHAR(36) NOT NULL ,
  `email` VARCHAR(36) NULL ,
  `qq` VARCHAR(16) NULL ,
  `cellphone` VARCHAR(16) NULL ,
  `living_country` VARCHAR(36) NULL ,
  `living_province` VARCHAR(36) NULL ,
  `living_city` VARCHAR(36) NULL ,
  `sex` TINYINT NULL ,
  `sexuality` TINYINT NULL,
  `birthday` DATE NULL ,
  `emotion_status` TINYINT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) );
 */
class AccountInformation extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NN';
        $columns['account_id']          = 'VARCHAR(36) NOTNULL';
        $columns['email']               = 'VARCHAR(36)';
        $columns['qq']                  = 'VARCHAR(16)';
        $columns['cellphone']           = 'VARCHAR(16)';
        $columns['living_country']      = 'VARCHAR(36)';
        $columns['living_province']     = 'VARCHAR(36)';
        $columns['living_city']         = 'VARCHAR(36)';
        $columns['sex']                 = 'TINYINT';
        $columns['sexuality']           = 'TINYINT';
        $columns['birthday']            = 'DATE';
        $columns['emotion_status']      = 'TINYINT';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_information';
    }
}
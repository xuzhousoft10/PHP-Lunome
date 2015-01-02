<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $created_at
 * @property string $produced_by
 * @property string $status
 * @property string $view
 * @property string $source_model
 * @property string $source_id
 * @property string $recipient_id
 * 
CREATE TABLE `account_notifications` (
  `id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `produced_by` varchar(36) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `content` varchar(256) DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `view` varchar(128) DEFAULT NULL,
  `source_model` varchar(128) DEFAULT NULL,
  `source_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


 **/
class AccountNotificationModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['created_at']  = 'DATETIME';
        $columns['produced_by'] = 'VARCHAR(36)';
        $columns['status']      = 'TINYINT ['.self::STATUS_NEW.']';
        $columns['view']        = 'VARCHAR(128)';
        $columns['source_model']= 'VARCHAR(128)';
        $columns['source_id']   = 'VARCHAR(36)';
        $columns['recipient_id']= 'VARCHAR(36)';
        return $columns;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_notifications';
    }
    
    const STATUS_NEW = 1;
    const STATUS_CLOSED = 2;
}
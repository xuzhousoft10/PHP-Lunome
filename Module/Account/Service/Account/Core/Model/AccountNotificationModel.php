<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $created_at
 * @property string $produced_by
 * @property string $status
 * @property string $view
 * @property string $source_model
 * @property string $source_id
 * @property string $recipient_id
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
    
    /**
     * @var integer
     */
    const STATUS_NEW = 1;
    
    /**
     * @var integer
     */
    const STATUS_CLOSED = 2;
}
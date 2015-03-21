<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $requester_id
 * @property string $recipient_id
 * @property string $message
 * @property string $request_started_at
 * @property string $is_agreed
 * @property string $result_message
 * @property string $answered_at
 **/
class AccountFriendshipRequestModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['requester_id']        = 'VARCHAR(36) NOTNULL';
        $columns['recipient_id']        = 'VARCHAR(36) NOTNULL';
        $columns['message']             = 'VARCHAR(64)';
        $columns['request_started_at']  = 'DATETIME';
        $columns['is_agreed']           = 'TINYINT';
        $columns['result_message']      = 'VARCHAR(64)';
        $columns['answered_at']         = 'DATETIME';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_friendship_requests';
    }
}
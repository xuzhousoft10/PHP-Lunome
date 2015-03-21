<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $writer_id
 * @property string $reader_id
 * @property string $wrote_at
 * @property string $content
 * @property string $status
 **/
class AccountChatContentModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NN';
        $columns['writer_id']   = 'VARCHAR(36) NN';
        $columns['reader_id']   = 'VARCHAR(36)';
        $columns['wrote_at']    = 'DATETIME';
        $columns['content']     = 'VARCHAR(1024)';
        $columns['status']      = 'TINYINT';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_chat_contents';
    }
}
<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $account_id
 * @property string $server_name
 * @property string $openid
 * @property string $access_token
 * @property string $refresh_token
 * @property string $expired_at
 **/
class AccountOauth20Model extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['account_id']      = 'VARCHAR(36) NOTNULL';
        $columns['server_name']     = 'VARCHAR(36) NOTNULL';
        $columns['openid']          = 'VARCHAR(36) NOTNULL';
        $columns['access_token']    = 'VARCHAR(36) NOTNULL';
        $columns['refresh_token']   = 'VARCHAR(36)';
        $columns['expired_at']      = 'DATETIME NOTNULL';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_oauth_20';
    }
}
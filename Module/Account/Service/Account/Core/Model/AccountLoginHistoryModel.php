<?php
namespace X\Module\Account\Service\Account\Core\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_id
 * @property string $time
 * @property string $ip
 * @property string $logined_by
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $isp
 **/
class AccountLoginHistoryModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NN';
        $columns['account_id']  = 'VARCHAR(36) NN';
        $columns['time']        = 'DATETIME NN';
        $columns['ip']          = 'VARCHAR(64) NN';
        $columns['logined_by']  = 'VARCHAR(32) NN';
        $columns['country']     = 'VARCHAR(64)';
        $columns['province']    = 'VARCHAR(64)';
        $columns['city']        = 'VARCHAR(64)';
        $columns['isp']         = 'VARCHAR(64)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_login_history';
    }
}
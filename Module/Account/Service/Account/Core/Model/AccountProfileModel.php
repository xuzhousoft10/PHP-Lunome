<?php
namespace X\Module\Account\Service\Account\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $account_id
 * @property string $account_number
 * @property string $email
 * @property string $qq
 * @property string $cellphone
 * @property string $living_country
 * @property string $living_province
 * @property string $living_city
 * @property string $sex
 * @property string $sexuality
 * @property string $birthday
 * @property string $emotion_status
 * @property string $is_lunar_calendar
 * @property string $photo
 * @property string $nickname
 */
class AccountProfileModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NN';
        $columns['account_id']          = 'VARCHAR(36) NOTNULL';
        $columns['account_number']      = 'INT NOTNULL UNSIGNED';
        $columns['email']               = 'VARCHAR(36)';
        $columns['qq']                  = 'VARCHAR(16)';
        $columns['cellphone']           = 'VARCHAR(16)';
        $columns['living_country']      = 'VARCHAR(36)';
        $columns['living_province']     = 'VARCHAR(36)';
        $columns['living_city']         = 'VARCHAR(36)';
        $columns['sex']                 = 'TINYINT';
        $columns['sexuality']           = 'TINYINT';
        $columns['is_lunar_calendar']   = 'TINYINT';
        $columns['birthday']            = 'DATE';
        $columns['emotion_status']      = 'TINYINT';
        $columns['nickname']            = 'VARCHAR(64)';
        $columns['photo']               = 'VARCHAR(256)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_profiles';
    }
}
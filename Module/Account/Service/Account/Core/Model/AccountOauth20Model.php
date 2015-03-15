<?php
namespace X\Module\Account\Service\Account\Core\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $server
 * @property string $openid
 * @property string $access_token
 * @property string $refresh_token
 * @property string $expired_at
 * 
 * 
CREATE TABLE `oauth_20` (
  `id` varchar(36) NOT NULL,
  `server` varchar(36) NOT NULL,
  `openid` varchar(36) NOT NULL,
  `access_token` varchar(36) NOT NULL,
  `refresh_token` varchar(36) NOT NULL,
  `expired_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$
 **/
class AccountOauth20Model extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['server']          = 'VARCHAR(36) NOTNULL';
        $columns['openid']          = 'VARCHAR(36) NOTNULL';
        $columns['access_token']    = 'VARCHAR(36) NOTNULL';
        $columns['refresh_token']   = 'VARCHAR(36) NOTNULL';
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
    
    /* OAuthr services */
    const SERVER_QQ = 'qq';
    const SERVER_SINA = 'sina';
}
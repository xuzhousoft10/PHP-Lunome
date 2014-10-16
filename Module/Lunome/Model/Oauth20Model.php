<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;
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
class Oauth20Model extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('server')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('openid')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('access_token')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('refresh_token')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('expired_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'oauth_20';
    }
    
    /* OAuthr services */
    const SERVER_QQ = 'qq';
}
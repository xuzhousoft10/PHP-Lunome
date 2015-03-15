<?php
namespace X\Module\Account\Service\Account\Core\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_me
 * @property string $account_friend
 * @property string $started_at
 * @property string $is_chatting
 * @property string $is_unread_notification_sended
 * 
 * delimiter $$

CREATE TABLE `account_friendships` (
  `id` varchar(36) NOT NULL,
  `account_me` varchar(36) NOT NULL,
  `account_friend` varchar(36) NOT NULL,
  `started_at` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


 **/
class AccountFriendshipModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                                  = 'PRIMARY VARCHAR(36) NN';
        $columns['account_me']                          = 'VARCHAR(36) NN';
        $columns['account_friend']                      = 'VARCHAR(36)';
        $columns['started_at']                          = 'DATETIME';
        $columns['is_chatting']                         = 'TINYINT [0]';
        $columns['is_unread_notification_sended']       = 'TINYINT [0]';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_friendships';
    }
}
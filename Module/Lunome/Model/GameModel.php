<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $category
 * @property string $is_multi_player
 * @property string $screen_dimension
 * @property string $area
 * @property string $published_at
 * @property string $published_by
 * @property string $developed_by
 * @property string $introduction
 **/
class GameModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']                = 'VARCHAR(128) NOTNULL';
        $columns['category']            = 'VARCHAR(128) NOTNULL';
        $columns['is_multi_player']     = 'INT UNSIGNED';
        $columns['screen_dimension']    = 'TINYINT UNSIGNED';
        $columns['area']                = 'VARCHAR(128)';
        $columns['published_at']        = 'TINYINT UNSIGNED';
        $columns['published_by']        = 'VARCHAR(128)';
        $columns['developed_by']        = 'VARCHAR(128)';
        $columns['introduction']        = 'VARCHAR(128)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_games';
    }
}
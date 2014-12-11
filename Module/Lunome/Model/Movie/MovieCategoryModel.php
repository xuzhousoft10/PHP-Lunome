<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $count
 * @property string $beg_message
 * @property string $recommend_message
 * @property string $share_message
 **/
class MovieCategoryModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']                = 'VARCHAR(45)';
        $columns['count']               = 'INT UNSIGNED';
        $columns['beg_message']         = 'VARCHAR(256)';
        $columns['recommend_message']   = 'VARCHAR(256)';
        $columns['share_message']       = 'VARCHAR(256)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_categories';
    }
}
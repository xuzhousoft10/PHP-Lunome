<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $region
 * @property string $status
 * @property string $magazine
 * @property string $press
 * @property string $published_at
 * @property string $episode_count
 * @property string $volume_count
 * @property string $category
 * @property string $premiered_at
 * @property string $character
 * @property string $introduction
 **/
class ComicModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']            = 'VARCHAR(128)';
        $columns['author']          = 'VARCHAR(128)';
        $columns['region']          = 'VARCHAR(128)';
        $columns['status']          = 'VARCHAR(128)';
        $columns['magazine']        = 'VARCHAR(128)';
        $columns['press']           = 'VARCHAR(128)';
        $columns['published_at']    = 'VARCHAR(128)';
        $columns['finished_at']     = 'VARCHAR(128)';
        $columns['episode_count']   = 'VARCHAR(128)';
        $columns['volume_count']    = 'VARCHAR(128)';
        $columns['category']        = 'VARCHAR(128)';
        $columns['premiered_at']    = 'VARCHAR(128)';
        $columns['character']       = 'VARCHAR(128)';
        $columns['introduction']    = 'VARCHAR(128)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_comics';
    }
}
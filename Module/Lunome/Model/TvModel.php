<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $episode_count
 * @property string $episode_length
 * @property string $season_count
 * @property string $premiered_at
 * @property string $region
 * @property string $language
 * @property string $category
 * @property string $director
 * @property string $writer
 * @property string $producer
 * @property string $executive
 * @property string $actor
 * @property string $introduction
 **/
class TvModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']            = 'VARCHAR(36) NOTNULL';
        $columns['episode_count']   = 'INT UNSIGNED';
        $columns['episode_length']  = 'INT UNSIGNED';
        $columns['season_count']    = 'INT UNSIGNED';
        $columns['premiered_at']    = 'DATE';
        $columns['region']          = 'VARCHAR(128)';
        $columns['language']        = 'VARCHAR(64)';
        $columns['category']        = 'VARCHAR(128)';
        $columns['director']        = 'VARCHAR(128)';
        $columns['writer']          = 'VARCHAR(128)';
        $columns['producer']        = 'VARCHAR(128)';
        $columns['executive']       = 'VARCHAR(128)';
        $columns['actor']           = 'VARCHAR(256)';
        $columns['introduction']    = 'VARCHAR(1024)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_tvs';
    }
}
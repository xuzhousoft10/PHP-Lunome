<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $movie_id
 * @property string $description
 * @property string $url
 * @property string $record_created_at
 * @property string $record_created_by
 **/
class MoviePosterModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULl';
        $columns['description']         = 'VARCHAR(1024)';
        $columns['url']                 = 'VARCHAR(256)';
        $columns['record_created_at']   = 'DATETIME';
        $columns['record_created_by']   = 'VARCHAR(36)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_posters';
    }
}
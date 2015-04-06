<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $name
 * @property string $movie_id
 * @property string $description
 * @property string $type
 * @property string $photo_url
 * @property string $record_created_at
 * @property string $record_created_by
 **/
class MovieCharacterModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULL';
        $columns['name']                = 'VARCHAR(64) NOTNULL';
        $columns['description']         = 'VARCHAR(1024)';
        $columns['type']                = 'INT [0]';
        $columns['photo_url']           = 'VARCHAR(256)';
        $columns['record_created_at']   = 'DATETIME';
        $columns['record_created_by']   = 'VARCHAR(36)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_characters';
    }
}
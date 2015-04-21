<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $movie_id
 * @property string $title
 * @property string $link
 * @property string $content
 * @property string $record_created_at
 * @property string $record_created_by
 **/
class MovieCriticismModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULL';
        $columns['title']               = 'VARCHAR(256) NOTNULL';
        $columns['content']             = 'VARCHAR(4096) NOTNULL';
        $columns['record_created_at']   = 'DATETIME NOTNULL';
        $columns['record_created_by']   = 'VARCHAR(36) NOTNULL';
        return $columns;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_criticisms';
    }
}
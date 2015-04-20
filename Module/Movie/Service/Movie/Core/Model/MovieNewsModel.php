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
 * @property string $time
 * @property string $source
 * @property string $logo
 * @property string $record_created_at
 * @property string $record_created_by
 **/
class MovieNewsModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULL';
        $columns['title']               = 'VARCHAR(256) NOTNULL';
        $columns['link']                = 'VARCHAR(1024) NOTNULL';
        $columns['time']                = 'DATE';
        $columns['source']              = 'VARCHAR(64)';
        $columns['logo']                = 'VARCHAR(256)';
        $columns['record_created_at']   = 'DATETIME';
        $columns['record_created_by']   = 'VARCHAR(36)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_news';
    }
}
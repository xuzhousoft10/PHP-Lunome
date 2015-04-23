<?php
namespace X\Module\Movie\Service\Movie\Core\Model\Interaction;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $movie_id
 * @property string $comment
 * @property string $recipient_id
 * @property string $record_created_at
 * @property string $record_created_by
 **/
class MovieSuggestionModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULL';
        $columns['comment']             = 'VARCHAR(512)';
        $columns['recipient_id']        = 'VARCHAR(36) NOTNULL';
        $columns['record_created_at']   = 'DATETIME';
        $columns['record_created_by']   = 'VARCHAR(36)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_suggestions';
    }
}
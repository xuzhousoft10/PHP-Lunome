<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Util\Model\ShortComment;
/**
 * 
 **/
class MovieCharacterCommentModel extends ShortComment {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_character_comments';
    }
}
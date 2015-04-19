<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Util\Model\Favourite;
/**
 * 
 **/
class MovieShortCommentFavouriteModel extends Favourite {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_short_comment_favourites';
    }
}
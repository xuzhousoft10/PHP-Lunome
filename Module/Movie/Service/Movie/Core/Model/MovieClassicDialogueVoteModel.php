<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Util\Model\Vote;
/**
 * 
 **/
class MovieClassicDialogueVoteModel extends Vote {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_classic_dialogue_votes';
    }
}
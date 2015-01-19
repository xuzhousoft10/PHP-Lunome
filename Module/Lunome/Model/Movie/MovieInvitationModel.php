<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $account_id
 * @property string $movie_id
 * @property string $inviter_id
 * @property string $invited_at
 * @property string $comment
 * @property string $answer
 * @property string $answered_at
 * @property string $answer_comment
 **/
class MovieInvitationModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['account_id']      = 'VARCHAR(36) NOTNULL';
        $columns['movie_id']        = 'VARCHAR(36) NOTNULL';
        $columns['inviter_id']      = 'VARCHAR(36) NOTNULL';
        $columns['invited_at']      = 'DATETIME';
        $columns['comment']         = 'VARCHAR(1024)';
        $columns['answer']          = 'TINYINT [0]';
        $columns['answered_at']     = 'DATETIME';
        $columns['answer_comment']  = 'VARCHAR(1024)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_invitations';
    }
}
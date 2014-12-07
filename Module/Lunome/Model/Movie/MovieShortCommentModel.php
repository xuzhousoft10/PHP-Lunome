<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $movie_id
 * @property string $content
 * @property string $commented_at
 * @property string $commented_by
 * @property string $parent_id
 **/
class MovieShortCommentModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']        = 'VARCHAR(36) NOTNULL';
        $columns['content']         = 'VARCHAR(256)';
        $columns['commented_at']    = 'DATETIME NOTNULL';
        $columns['commented_by']    = 'VARCHAR(36) NOTNULL';
        $columns['parent_id']       = 'VARCHAR(36) NOTNULL';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_short_comments';
    }
}
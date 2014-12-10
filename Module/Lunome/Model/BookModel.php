<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $category
 * @property string $published_at
 * @property string $published_by
 * @property string $word_count
 * @property string $status
 * @property string $introduction
 **/
class BookModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']            = 'VARCHAR(128) NOTNULL';
        $columns['author']          = 'VARCHAR(128)';
        $columns['category']        = 'VARCHAR(128)';
        $columns['published_at']    = 'VARCHAR(128)';
        $columns['published_by']    = 'VARCHAR(128)';
        $columns['word_count']      = 'INT UNSIGNED';
        $columns['status']          = 'VARCHAR(128)';
        $columns['introduction']    = 'VARCHAR(512)';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_books';
    }
}
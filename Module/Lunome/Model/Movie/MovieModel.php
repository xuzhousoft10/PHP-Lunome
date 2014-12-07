<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $name
 * @property string $length
 * @property string $date
 * @property string $region_id
 * @property string $category
 * @property string $language_id
 * @property string $director
 * @property string $writer
 * @property string $producer
 * @property string $executive
 * @property string $actor
 * @property string $introduction
 * @property string $has_cover
 **/
class MovieModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']              = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['name']            = 'VARCHAR(128)';
        $columns['length']          = 'INT UNSIGNED';
        $columns['date']            = 'DATE';
        $columns['region_id']       = 'VARCHAR(36)';
        $columns['language_id']     = 'VARCHAR(36)';
        $columns['director']        = 'VARHCAR(128)';
        $columns['writer']          = 'VARCHAR(128)';
        $columns['producer']        = 'VARCHAR(128)';
        $columns['executive']       = 'VARCHAR(128)';
        $columns['actor']           = 'VARCHAR(256)';
        $columns['introduction']    = 'VARCHAR(1024)';
        $columns['has_cover']       = 'TINYINT [0]';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movies';
    }
}
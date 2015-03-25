<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $name
 * @property string $length
 * @property string $date
 * @property string $region_id
 * @property string $category
 * @property string $language_id
 * @property string $writer
 * @property string $producer
 * @property string $executive
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
        $columns['introduction']    = 'VARCHAR(2048)';
        $columns['has_cover']       = 'TINYINT [0]';
        $columns['url']             = 'VARCHAR(255)';
        $columns['source']          = 'VARCHAR(45)';
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
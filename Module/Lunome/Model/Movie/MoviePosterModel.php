<?php
namespace X\Module\Lunome\Model\Movie;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
CREATE TABLE `movie_posters` (
  `id` varchar(36) NOT NULL,
  `movie_id` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/


/**
 * @property string $id
 * @property string $movie_id
 **/
class MoviePosterModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']                  = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['movie_id']            = 'VARCHAR(36) NOTNULl';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_posters';
    }
}
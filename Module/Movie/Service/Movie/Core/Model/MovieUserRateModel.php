<?php
namespace X\Module\Movie\Service\Movie\Core\Model;
/**
 * 
 */
use X\Module\Lunome\Util\Model\Basic;
/**
 * @property string $id
 * @property string $account_id
 * @property string $movie_id
 * @property string $score
 **/
class MovieUserRateModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']             = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['account_id']     = 'VARCHAR(36)';
        $columns['movie_id']       = 'VARCHAR(36)';
        $columns['score']          = 'INT UNSIGNED';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_user_rates';
    }
}
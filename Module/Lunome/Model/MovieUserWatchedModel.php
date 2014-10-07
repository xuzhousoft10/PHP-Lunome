<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $movie_id
 * @property string $account_id
 **/
class MovieUserWatchedModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(32)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('movie_id')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setLength(32)->setNullable(false);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'movie_user_watched';
    }
}
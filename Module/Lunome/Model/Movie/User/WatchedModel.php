<?php
namespace X\Module\Lunome\Model\Movie\User;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $account_id
 * @property string $movie_id
 * @property string $rate
 **/
class WatchedModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('movie_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('rate')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
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
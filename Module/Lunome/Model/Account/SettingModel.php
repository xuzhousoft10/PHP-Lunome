<?php
namespace X\Module\Lunome\Model\Account;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $account_id
 * @property string $language
 * @property string $timezone
 * @property string $theme
 * @property string $header_image
 * @property string $background_image
 * @property string $notification_new_movie
 * @property string $notification_new_tv
 * @property string $notification_new_book
 **/
class SettingModel extends \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_INT)->setIsPrimaryKey(true)->setIsUnsigned(true)->setNullable(false)->setIsAutoIncrement(true);
        $columns[] = Column::create('account_id')->setType(ColumnType::T_INT)->setIsUnsigned(true)->setNullable(false);
        $columns[] = Column::create('language')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('timezone')->setType(ColumnType::T_TINYINT);
        $columns[] = Column::create('theme')->setType(ColumnType::T_SMALLINT)->setIsUnsigned(true);
        $columns[] = Column::create('header_image')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('background_image')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_movie')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_tv')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $columns[] = Column::create('notification_new_book')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'account_settings';
    }
}
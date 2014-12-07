<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;

/**
 * @property string $id
 * @property string $media_type
 * @property string $media_id
 * @property string $account_id
 * @property string $mark
 **/
class MediaUserMarksModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns['id']          = 'PRIMARY VARCHAR(36) NOTNULL';
        $columns['media_type']  = 'VARCHAR(128) NOTNULL';
        $columns['media_id']    = 'VARCHAR(36) NOTNULL';
        $columns['account_id']  = 'VARCHAR(36) NOTNULL';
        $columns['mark']        = 'TINYINT NOTNULL';
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_user_marks';
    }
}
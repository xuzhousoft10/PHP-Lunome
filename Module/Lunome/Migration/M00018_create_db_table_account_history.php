<?php
/** 
 * Migration file for create_db_table_account_history 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00018_create_db_table_account_history 
 */
class M00018_create_db_table_account_history extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $id         = Column::create('id')->setType(ColumnType::T_VARCHAR)->setNullable(false)->setLength(36);
        $accountId  = Column::create('account_id')->setType(ColumnType::T_VARCHAR)->setNullable(false)->setLength(36);
        $time       = Column::create('time')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $action     = Column::create('action')->setType(ColumnType::T_VARCHAR)->setLength(64)->setNullable(false);
        $target     = Column::create('target')->setType(ColumnType::T_VARCHAR)->setLength(36);
        $code       = Column::create('code')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true)->setDefault(0);
        $message    = Column::create('message')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $comment    = Column::create('comment')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns    = array($id, $accountId, $time, $action, $target, $code, $message, $comment);
        
        $accountHistory = Manager::create('account_history', $columns, 'id');
        $accountHistory->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('account_history')->drop();
    }
}
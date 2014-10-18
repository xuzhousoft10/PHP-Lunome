<?php
/** 
 * Migration file for create_db_table_oauth_20 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/** 
 * M00007_create_db_table_oauth_20 
 */
class M00007_create_db_table_oauth_20 extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('server')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('openid')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('access_token')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('refresh_token')->setType(ColumnType::T_VARCHAR)->setLength(36)->setNullable(false);
        $columns[] = Column::create('expired_at')->setType(ColumnType::T_DATETIME)->setNullable(false);
        $table = TableManager::create('oauth_20', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('oauth_20')->drop();
    }
}
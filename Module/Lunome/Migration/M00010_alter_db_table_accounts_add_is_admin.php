<?php
/** 
 * Migration file for alter_db_table_accounts_add_is_admin 
 */
namespace X\Module\Lunome\Migration;

use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;

/** 
 * M00010_alter_db_table_accounts_add_is_admin 
 */
class M00010_alter_db_table_accounts_add_is_admin extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $column = Column::create('is_admin')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true)->setDefault(0);
        Manager::open('accounts')->addColumn($column);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('accounts')->dropColumn('is_admin');
    }
}
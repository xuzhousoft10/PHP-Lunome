<?php
/** 
 * Migration file for create_db_table_languages 
 */
namespace X\Module\Lunome\Migration;

use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
/** 
 * M00006_create_db_table_languages 
 */
class M00006_create_db_table_system_languages extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns = Column::create('id')->varchar(13)->notNull();
        $columns = Column::create('name')->varchar(64)->notNull();
        $table = Manager::create('system_languages', $columns);
        $table->addPrimaryKey('id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('system_languages')->drop();
    }
}
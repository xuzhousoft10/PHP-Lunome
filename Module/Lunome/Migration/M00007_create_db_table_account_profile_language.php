<?php
/** 
 * Migration file for create_db_table_account_profile_language 
 */
namespace X\Module\Lunome\Migration;

use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
/** 
 * M00007_create_db_table_account_profile_language 
 */
class M00007_create_db_table_account_profile_language extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->varchar(13)->notNull();
        $columns[] = Column::create('language_id')->varchar(13)->notNull();
        $table = Manager::create('account_profile_language', $columns);
        $table->addPrimaryKey('id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('account_profile_lanaguage')->drop();
    }
}
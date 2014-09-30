<?
/** 
 * Migration file for create_db_table_accounts 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00000_create_db_table_accounts 
 */
class M00000_create_db_table_accounts extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->varchar(13)->notNull();
        $columns[] = Column::create('account')->int()->notNull();
        $columns[] = Column::create('created_at')->datetime()->notNull();
        $columns[] = Column::create('status')->int(Column::T_TINYINT)->notNull()->defaultVal(0);
        $table = TableManager::create('accounts', $columns);
        
        $table->addPrimaryKey('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('accounts')->drop();
    }
}
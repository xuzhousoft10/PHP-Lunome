<?php
/** 
 * Migration file for delete_db_table_media_posters 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00020_delete_db_table_media_posters 
 */
class M00020_delete_db_table_media_posters extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        Manager::open('media_posters')->drop();
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        
    }
}
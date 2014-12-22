<?php
/** 
 * Migration file for insert_init_configurations 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00022_insert_init_configurations 
 */
class M00022_insert_init_configurations extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $table = Manager::open('configurations');
        $table->insert(array(
            'id'            =>'00000000-0000-0000-0000-000000000000', 
            'name'          =>'login_background_image', 
            'value'         =>'http://7sbnm1.com1.z0.glb.clouddn.com/configurations/login_background.jpg',
            'updated_at'    => date('Y-m-d H:i:s', time()),
            'updated_by'    => 'MIGRATION_INIT',
        ));
        
        $table->insert(array(
            'id'            =>'00000000-0000-0000-0000-000000000001',
            'name'          =>'default_movie_cover_image',
            'value'         =>'http://7sbnm1.com1.z0.glb.clouddn.com/configurations/default_movie_cover.png',
            'updated_at'    => date('Y-m-d H:i:s', time()),
            'updated_by'    => 'MIGRATION_INIT',
        ));
        
        $table->insert(array(
            'id'            =>'00000000-0000-0000-0000-000000000002',
            'name'          =>'qq_login_icon',
            'value'         =>'http://7sbnm1.com1.z0.glb.clouddn.com/configurations/qq_login.png',
            'updated_at'    => date('Y-m-d H:i:s', time()),
            'updated_by'    => 'MIGRATION_INIT',
        ));
        
        $table->insert(array(
            'id'            =>'00000000-0000-0000-0000-000000000003',
            'name'          =>'media_item_operation_waiting_image',
            'value'         =>'http://7sbnm1.com1.z0.glb.clouddn.com/configurations/waitting.gif',
            'updated_at'    => date('Y-m-d H:i:s', time()),
            'updated_by'    => 'MIGRATION_INIT',
        ));
        
        $table->insert(array(
            'id'            =>'00000000-0000-0000-0000-000000000004',
            'name'          =>'media_loader_loading_image',
            'value'         =>'http://7sbnm1.com1.z0.glb.clouddn.com/configurations/loadding.gif',
            'updated_at'    => date('Y-m-d H:i:s', time()),
            'updated_by'    => 'MIGRATION_INIT',
        ));
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('configurations')->truncate();
    }
}
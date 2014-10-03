<?php
/** 
 * Migration file for create_db_table_tvs 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00050_create_db_table_tvs 
 */
class M00050_create_db_table_tvs extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('name')->varchar(256)->notNull();
        $columns[] = Column::create('production_company_id')->int()->unsigned();
        $columns[] = Column::create('region_id')->int()->unsigned();
        $columns[] = Column::create('episode_count')->setType(Column::T_SMALLINT)->unsigned();
        $columns[] = Column::create('episode_lenght')->setType(Column::T_SMALLINT)->unsigned();
        $columns[] = Column::create('season_count')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('language_id')->int()->unsigned();
        $columns[] = Column::create('color')->setType(Column::T_TINYINT)->unsigned();
        $table = TableManager::create('tvs', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('tvs')->drop();
    }
}
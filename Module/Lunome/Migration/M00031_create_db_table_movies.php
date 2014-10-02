<?php
/** 
 * Migration file for create_db_table_movies 
 */
namespace X\Module\Lunome\Migration;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\Table\Manager as TableManager;
use X\Service\XDatabase\Core\Table\Column;

/** 
 * M00031_create_db_table_movies 
 */
class M00031_create_db_table_movies extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $columns = array();
        $columns[] = Column::create('id')->int()->unsigned()->notNull()->autoIncrement();
        $columns[] = Column::create('name')->varchar(256)->notNull();
        $columns[] = Column::create('region_id')->int()->unsigned();
        $columns[] = Column::create('length')->setType(Column::T_SMALLINT)->unsigned();
        $columns[] = Column::create('overview')->varchar(1024);
        $columns[] = Column::create('is_adult')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('language_id')->int()->unsigned();
        $columns[] = Column::create('released_at')->date();
        $columns[] = Column::create('directors_id')->int()->unsigned()->notNull();
        $columns[] = Column::create('budget')->int()->unsigned();
        $columns[] = Column::create('color')->setType(Column::T_TINYINT)->unsigned();
        $columns[] = Column::create('production_company_id')->int()->unsigned();
        $columns[] = Column::create('box_office')->int()->unsigned();
        $table = TableManager::create('movies', $columns, 'id');
        $table->addUnique('id');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        TableManager::open('movies')->drop();
    }
}
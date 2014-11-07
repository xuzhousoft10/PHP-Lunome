<?php
/** 
 * Migration file for alter_db_table_add_prefix_media_to_media_tables 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00013_alter_db_table_add_prefix_media_to_media_tables 
 */
class M00013_alter_db_table_add_prefix_media_to_media_tables extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        Manager::open('movies')->rename('media_movies');
        Manager::open('tvs')->rename('media_tvs');
        Manager::open('games')->rename('media_games');
        Manager::open('books')->rename('media_books');
        Manager::open('comics')->rename('media_comics');
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_movies')->rename('movies');
        Manager::open('media_tvs')->rename('tvs');
        Manager::open('media_games')->rename('games');
        Manager::open('media_books')->rename('books');
        Manager::open('media_comics')->rename('comics');
    }
}
<?php
/** 
 * Migration file for alter_db_table_tvs_to_add_more_attributes 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;

/** 
 * M00014_alter_db_table_tvs_to_add_more_attributes 
 */
class M00014_alter_db_table_tvs_to_add_more_attributes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $episodeCount   = Column::create('episode_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $episodeLength  = Column::create('episode_length')->setType(ColumnType::T_INT)->setIsUnsigned(true); 
        $seasonCount    = Column::create('season_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $premieredAt    = Column::create('premiered_at')->setType(ColumnType::T_DATE); 
        $region         = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $language       = Column::create('language')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $category       = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $director       = Column::create('director')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $writer         = Column::create('writer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $producer       = Column::create('producer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $executive      = Column::create('executive')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $actor          = Column::create('actor')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $introduction   = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(1024);
        
        Manager::open('media_tvs')->addColumn($episodeCount);
        Manager::open('media_tvs')->addColumn($episodeLength);
        Manager::open('media_tvs')->addColumn($seasonCount);
        Manager::open('media_tvs')->addColumn($premieredAt);
        Manager::open('media_tvs')->addColumn($region);
        Manager::open('media_tvs')->addColumn($language);
        Manager::open('media_tvs')->addColumn($category);
        Manager::open('media_tvs')->addColumn($director);
        Manager::open('media_tvs')->addColumn($writer);
        Manager::open('media_tvs')->addColumn($producer);
        Manager::open('media_tvs')->addColumn($executive);
        Manager::open('media_tvs')->addColumn($actor);
        Manager::open('media_tvs')->addColumn($introduction);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_tvs')->addColumn('episode_count');
        Manager::open('media_tvs')->addColumn('episode_length');
        Manager::open('media_tvs')->addColumn('season_count');
        Manager::open('media_tvs')->addColumn('premiered_at');
        Manager::open('media_tvs')->addColumn('region');
        Manager::open('media_tvs')->addColumn('language');
        Manager::open('media_tvs')->addColumn('category');
        Manager::open('media_tvs')->addColumn('director');
        Manager::open('media_tvs')->addColumn('writer');
        Manager::open('media_tvs')->addColumn('producer');
        Manager::open('media_tvs')->addColumn('executive');
        Manager::open('media_tvs')->addColumn('actor');
        Manager::open('media_tvs')->addColumn('introduction');
    }
}
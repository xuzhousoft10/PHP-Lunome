<?php
/** 
 * Migration file for alter_db_table_games_to_add_more_attributes 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00017_alter_db_table_games_to_add_more_attributes 
 */
class M00017_alter_db_table_games_to_add_more_attributes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $category       = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $isMultiPlayer  = Column::create('is_multi_player')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $screenDimension= Column::create('screen_dimension')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $area           = Column::create('area')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $publishedAt    = Column::create('published_at')->setType(ColumnType::T_TINYINT)->setIsUnsigned(true);
        $publishedBy    = Column::create('published_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $developedBy    = Column::create('developed_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $introduction   = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(128);
        
        Manager::open('media_games')->addColumn($category);
        Manager::open('media_games')->addColumn($isMultiPlayer);
        Manager::open('media_games')->addColumn($screenDimension);
        Manager::open('media_games')->addColumn($area);
        Manager::open('media_games')->addColumn($publishedAt);
        Manager::open('media_games')->addColumn($publishedBy);
        Manager::open('media_games')->addColumn($developedBy);
        Manager::open('media_games')->addColumn($introduction);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_games')->dropColumn('category');
        Manager::open('media_games')->dropColumn('is_multi_player');
        Manager::open('media_games')->dropColumn('screen_dimension');
        Manager::open('media_games')->dropColumn('area');
        Manager::open('media_games')->dropColumn('published_at');
        Manager::open('media_games')->dropColumn('published_by');
        Manager::open('media_games')->dropColumn('developed_by');
        Manager::open('media_games')->dropColumn('introduction');
    }
}
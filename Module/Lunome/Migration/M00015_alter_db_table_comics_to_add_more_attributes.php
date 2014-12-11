<?php
/** 
 * Migration file for alter_db_table_comics_to_add_more_attributes 
 */
namespace X\Module\Lunome\Migration;

use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager;
/** 
 * M00015_alter_db_table_comics_to_add_more_attributes 
 */
class M00015_alter_db_table_comics_to_add_more_attributes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $author         = Column::create('author')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $region         = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $status         = Column::create('status')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $magazine       = Column::create('magazine')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $press          = Column::create('press')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $publishedAt    = Column::create('published_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $finishedAt     = Column::create('finished_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $episodeCount   = Column::create('episode_count')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $volumeCount    = Column::create('volume_count')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $category       = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $premieredAt    = Column::create('premiered_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $character      = Column::create('character')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $introduction   = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(128);
        
        Manager::open('media_comics')->addColumn($author);
        Manager::open('media_comics')->addColumn($region);
        Manager::open('media_comics')->addColumn($status);
        Manager::open('media_comics')->addColumn($magazine);
        Manager::open('media_comics')->addColumn($press);
        Manager::open('media_comics')->addColumn($publishedAt);
        Manager::open('media_comics')->addColumn($finishedAt);
        Manager::open('media_comics')->addColumn($episodeCount);
        Manager::open('media_comics')->addColumn($volumeCount);
        Manager::open('media_comics')->addColumn($category);
        Manager::open('media_comics')->addColumn($premieredAt);
        Manager::open('media_comics')->addColumn($character);
        Manager::open('media_comics')->addColumn($introduction);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_comics')->dropColumn('author');
        Manager::open('media_comics')->dropColumn('region');
        Manager::open('media_comics')->dropColumn('status');
        Manager::open('media_comics')->dropColumn('magazine');
        Manager::open('media_comics')->dropColumn('press');
        Manager::open('media_comics')->dropColumn('published_at');
        Manager::open('media_comics')->dropColumn('finished_at');
        Manager::open('media_comics')->dropColumn('episode_count');
        Manager::open('media_comics')->dropColumn('volume_count');
        Manager::open('media_comics')->dropColumn('category');
        Manager::open('media_comics')->dropColumn('premiered_at');
        Manager::open('media_comics')->dropColumn('character');
        Manager::open('media_comics')->dropColumn('introduction');
    }
}
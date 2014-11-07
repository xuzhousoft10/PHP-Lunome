<?php
/** 
 * Migration file for alter_db_table_movies_to_add_more_attributes 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;

/** 
 * M00012_alter_db_table_movies_to_add_more_attributes 
 */
class M00012_alter_db_table_movies_to_add_more_attributes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $length         = Column::create('length')->setIsUnsigned(true)->setType(ColumnType::T_INT);
        $year           = Column::create('year')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $region         = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $category       = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $language       = Column::create('language')->setType(ColumnType::T_VARCHAR)->setLength(64);
        $director       = Column::create('director')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $writer         = Column::create('writer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $producer       = Column::create('producer')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $executive      = Column::create('executive')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $actor          = Column::create('actor')->setType(ColumnType::T_VARCHAR)->setLength(256);
        $introduction   = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(1024);
        
        $movies = Manager::open('movies');
        $movies->addColumn($length);
        $movies->addColumn($year);
        $movies->addColumn($region);
        $movies->addColumn($category);
        $movies->addColumn($language);
        $movies->addColumn($director);
        $movies->addColumn($writer);
        $movies->addColumn($producer);
        $movies->addColumn($executive);
        $movies->addColumn($actor);
        $movies->addColumn($introduction);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        $movies = Manager::open('movies');
        $movies->dropColumn('length');
        $movies->dropColumn('year');
        $movies->dropColumn('region');
        $movies->dropColumn('category');
        $movies->dropColumn('language');
        $movies->dropColumn('director');
        $movies->dropColumn('writer');
        $movies->dropColumn('producer');
        $movies->dropColumn('executive');
        $movies->dropColumn('actor');
        $movies->dropColumn('introduction');
    }
}
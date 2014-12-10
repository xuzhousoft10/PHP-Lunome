<?php
/** 
 * Migration file for alter_db_table_books_to_add_more_attributes 
 */
namespace X\Module\Lunome\Migration;

/**
 * 
 */
use X\Service\XDatabase\Core\Table\Column;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\Table\Manager;

/** 
 * M00016_alter_db_table_books_to_add_more_attributes 
 */
class M00016_alter_db_table_books_to_add_more_attributes extends \X\Core\Module\Migrate {
    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::up()
     */
    public function up() {
        $author         = Column::create('author')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $category       = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $publishedAt    = Column::create('published_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $publishedBy    = Column::create('published_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $wordCount      = Column::create('word_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $status         = Column::create('status')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $introduction   = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(512);
        
        Manager::open('media_books')->addColumn($author);
        Manager::open('media_books')->addColumn($category);
        Manager::open('media_books')->addColumn($publishedAt);
        Manager::open('media_books')->addColumn($publishedBy);
        Manager::open('media_books')->addColumn($wordCount);
        Manager::open('media_books')->addColumn($status);
        Manager::open('media_books')->addColumn($introduction);
    }

    /** 
     * (non-PHPdoc)
     * @see \X\Core\Module\InterfaceMigrate::down()
     */
    public function down() {
        Manager::open('media_books')->dropColumn('author');
        Manager::open('media_books')->dropColumn('category');
        Manager::open('media_books')->dropColumn('published_at');
        Manager::open('media_books')->dropColumn('published_by');
        Manager::open('media_books')->dropColumn('word_count');
        Manager::open('media_books')->dropColumn('status');
        Manager::open('media_books')->dropColumn('introduction');
    }
}
<?php
namespace X\Module\Lunome\Model;

/**
 * Use statements
 */
use X\Util\Model\Basic;
use X\Service\XDatabase\Core\Table\ColumnType;
use X\Service\XDatabase\Core\ActiveRecord\Column;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $category
 * @property string $published_at
 * @property string $published_by
 * @property string $word_count
 * @property string $status
 * @property string $introduction
 **/
class BookModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128)->setNullable(false);
        $columns[] = Column::create('author')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('published_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('published_by')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('word_count')->setType(ColumnType::T_INT)->setIsUnsigned(true);
        $columns[] = Column::create('status')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(512);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_books';
    }
}
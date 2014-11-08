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
 * @property string $region
 * @property string $status
 * @property string $magazine
 * @property string $press
 * @property string $published_at
 * @property string $episode_count
 * @property string $volume_count
 * @property string $category
 * @property string $premiered_at
 * @property string $character
 * @property string $introduction
 **/
class ComicModel extends Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::describe()
     */
    protected function describe() {
        $columns = array();
        $columns[] = Column::create('id')->setType(ColumnType::T_VARCHAR)->setLength(36)->setIsPrimaryKey(true)->setNullable(false);
        $columns[] = Column::create('name')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('author')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('region')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('status')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('magazine')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('press')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('published_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('finished_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('episode_count')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('volume_count')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('category')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('premiered_at')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('character')->setType(ColumnType::T_VARCHAR)->setLength(128);
        $columns[] = Column::create('introduction')->setType(ColumnType::T_VARCHAR)->setLength(128);
        return $columns;
    }

    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableName()
     */
    protected function getTableName() {
        return 'media_comics';
    }
}
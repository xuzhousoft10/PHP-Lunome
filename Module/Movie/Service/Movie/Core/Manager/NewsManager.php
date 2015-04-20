<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieNewsModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\News;
/**
 * 
 */
class NewsManager {
    /**
     * @var \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    private $movieInstance = null;
    
    /**
     * @param \X\Module\Movie\Service\Movie\Core\Instance\Movie $movie
     */
    public function __construct( $movie ) {
        $this->movieInstance = $movie;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\News[]
     */
    public function find( $condition=null ) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        } else {
            $criteria = $condition;
        }
        
        if ( !($criteria->condition instanceof ConditionBuilder) ) {
            $criteria->condition = ConditionBuilder::build($criteria->condition);
        }
        
        $criteria->condition->andAlso()->is('movie_id', $this->movieInstance->get('id'));
        $news = MovieNewsModel::model()->findAll($condition);
        foreach ( $news as $index => $newsItem ) {
            $news[$index] = new News($newsItem);
        }
        return $news;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\News
     */
    public function get($id) {
        $news = MovieNewsModel::model()->findByPrimaryKey($id);
        return (null===$news) ? null : new News($news);
    }
}
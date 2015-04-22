<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MoviePosterModel;
use X\Module\Movie\Service\Movie\Core\Instance\Poster;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
/**
 * 
 */
class PosterManager {
    /**
     * @var MovieModel
     */
    private $movieModel = null;
    
    /**
     * @var string
     */
    private $movieID = null;
    
    /**
     * @param MovieModel $movieModel
     */
    public function __construct( $movieModel ) {
        $this->movieModel = $movieModel;
        $this->movieID = $movieModel->id;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function find( $condition=null ) {
        $criteria = $condition;
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
        }

        if ( !($criteria->condition instanceof ConditionBuilder) ) {
            $criteria->condition = ConditionBuilder::build($criteria->condition);
        }
        
        $criteria->condition->andAlso()->is('movie_id', $this->movieID);
        $posters = MoviePosterModel::model()->findAll($condition);
        foreach ( $posters as $index => $poster ) {
            $posters[$index] = new Poster($poster);
        }
        return $posters;
    }
    
    /**
     * @param unknown $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function get( $id ) {
        $poster = MoviePosterModel::model()->findByPrimaryKey($id);
        return (null===$poster) ? null : new Poster($poster);
    }
    
    /**
     * @param string $condition
     * @return number
     */
    public function count( $condition=null ) {
        if ( !($condition instanceof ConditionBuilder) ) {
            $condition = ConditionBuilder::build($condition);
        }
        $condition->is('movie_id', $this->movieID);
        
        return MoviePosterModel::model()->count($condition);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function add() {
        $poster = new MoviePosterModel();
        $poster->movie_id = $this->movieID;
        return new Poster($poster);
    }
}
<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieShortCommentModel;
use X\Module\Movie\Service\Movie\Core\Instance\ShortComment;
/**
 * 
 */
class ShortCommentManager {
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
     * @return \X\Module\Movie\Service\Movie\Core\Instance\MovieShortCommentModel
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
        $criteria->addOrder('commented_at', 'DESC');
        $comments = MovieShortCommentModel::model()->findAll($condition);
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = new ShortComment($comment);
        }
        return $comments;
    }
    
    /**
     * 
     * @param unknown $id
     * @return Ambigous \X\Module\Movie\Service\Movie\Core\Instance\ShortComment
     */
    public function get( $id ) {
        $comment = MovieShortCommentModel::model()->findByPrimaryKey($id);
        return (null===$comment) ? null : new ShortComment($comment);
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
        
        return MovieShortCommentModel::model()->count($condition);
    }
}
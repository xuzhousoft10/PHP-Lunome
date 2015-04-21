<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieCriticismModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Criticism;
/**
 * 
 */
class CriticismManager {
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
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Criticism[]
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
        $criticisms = MovieCriticismModel::model()->findAll($condition);
        foreach ( $criticisms as $index => $criticism ) {
            $criticisms[$index] = new Criticism($criticism);
        }
        return $criticisms;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Criticism
     */
    public function get($id) {
        $criticism = MovieCriticismModel::model()->findByPrimaryKey($id);
        return (null===$criticism) ? null : new Criticism($criticism);
    }
}
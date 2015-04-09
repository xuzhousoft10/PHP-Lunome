<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterModel;
use X\Module\Movie\Service\Movie\Core\Instance\Character;
/**
 * 
 */
class CharacterManager {
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
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
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
        $characters = MovieCharacterModel::model()->findAll($criteria);
        foreach ( $characters as $index => $character ) {
            $characters[$index] = new Character($character);
        }
        return $characters;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function get( $id ) {
        $character = MovieCharacterModel::model()->findByPrimaryKey($id);
        if ( null === $character ) {
            return null;
        }
        return new Character($character);
    }
    
    /**
     * @param mixed $condition
     * @return number
     */
    public function count( $condition=null ) {
        if ( !($condition instanceof ConditionBuilder) ) {
            $condition = ConditionBuilder::build($condition);
        }
        $condition->is('movie_id', $this->movieID);
        
        return MovieCharacterModel::model()->count($condition);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function add() {
        $character = new MovieCharacterModel();
        $character->movie_id = $this->movieID;
        return new Character($character);
    }
}
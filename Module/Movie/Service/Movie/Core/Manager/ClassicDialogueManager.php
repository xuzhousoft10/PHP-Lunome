<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueModel;
use X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
/**
 * 
 */
class ClassicDialogueManager {
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
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue
     */
    public function find( $condition=null ) {
        $criteria = new Criteria();
        if ( !($condition instanceof Criteria) ) {
            $criteria->condition = $condition;
        } else {
            $criteria = $condition;
        }
        
        if ( !($criteria->condition instanceof ConditionBuilder) ) {
            $criteria->condition = ConditionBuilder::build($criteria->condition);
        }
        
        $criteria->condition->andAlso()->is('movie_id', $this->movieID);
        $dialogues = MovieClassicDialogueModel::model()->findAll($criteria);
        foreach ( $dialogues as $index => $dialogue ) {
            $dialogues[$index] = new ClassicDialogue($dialogue);
        }
        return $dialogues;
    }
    

    /**
     * @return number
     */
    public function count($condition=null) {
        if ( !($condition instanceof ConditionBuilder) ) {
            $condition = ConditionBuilder::build($condition);
        }
        $condition->is('movie_id', $this->movieID);
        
        return MovieClassicDialogueModel::model()->count($condition);
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue
     */
    public function get( $id ) {
        $dialogue = MovieClassicDialogueModel::model()->findByPrimaryKey($id);
        return (null===$dialogue) ? null : new ClassicDialogue($dialogue);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue
     */
    public function add() {
        $dialogue = new MovieClassicDialogueModel();
        $dialogue->movie_id = $this->movieID;
        return new ClassicDialogue($dialogue);
    }
}
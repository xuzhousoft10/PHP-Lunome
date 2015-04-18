<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueModel;
use X\Module\Movie\Service\Movie\Util\InteractionInstance;
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueFavouriteModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueVoteModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueCommentModel;
/**
 * 
 */
class ClassicDialogue extends InteractionInstance {
    /**
     * @var MovieClassicDialogueModel
     */
    private $model = null;
    
    /**
     * @var MovieModel
     */
    private $movie = null;
    
    /**
     * @param MovieClassicDialogueModel $model
     */
    public function __construct( $model, $movie ) {
        $this->model = $model;
        $this->movie = $movie;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->model->get($name);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue
     */
    public function set($name, $value) {
        $this->model->set($name, $value);
        return $this;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue
     */
    public function save() {
        $this->model->save();
        return $this;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function getCharacter() {
        $movie = new Movie($this->movie);
        return $movie->getCharacterManager()->get($this->get('character_id'));
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getFavouriteModel()
     */
    protected function getFavouriteModel() {
        return MovieClassicDialogueFavouriteModel::getClassName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getVoteModel()
     */
    protected function getVoteModel() {
        return MovieClassicDialogueVoteModel::getClassName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getCommentModel()
     */
    protected function getCommentModel() {
        return MovieClassicDialogueCommentModel::getClassName();
    }
}
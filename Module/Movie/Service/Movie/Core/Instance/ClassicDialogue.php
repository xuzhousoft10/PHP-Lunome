<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieClassicDialogueModel;
/**
 * 
 */
class ClassicDialogue {
    /**
     * @var MovieClassicDialogueModel
     */
    private $model = null;
    
    /**
     * @param MovieClassicDialogueModel $model
     */
    public function __construct( $model ) {
        $this->model = $model;
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
}
<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieActorModel;
/**
 * 
 */
class Actor {
    /**
     * @var MovieActorModel
     */
    private $model = null;
    
    /**
     * @param MovieActorModel $model
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
}
<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieDirectorModel;
/**
 * 
 */
class Director {
    /**
     * @var MovieDirectorModel
     */
    private $model = null;
    
    /**
     * @param MovieDirectorModel $model
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
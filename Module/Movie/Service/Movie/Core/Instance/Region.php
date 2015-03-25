<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieRegionModel;
/**
 * 
 */
class Region {
    /**
     * @var MovieRegionModel
     */
    private $regionModel = null;
    
    /**
     * @param MovieRegionModel $regionModel
     */
    public function __construct( $regionModel ) {
        $this->regionModel = $regionModel;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->regionModel->get($name);
    }
}
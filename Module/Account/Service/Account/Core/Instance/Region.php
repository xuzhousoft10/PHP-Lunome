<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountRegionModel;
/**
 *
 */
class Region {
    /**
     * @var AccountRegionModel
     */
    private $regionModel = null;
    
    /**
     * @param AccountRegionModel $region
     */
    public function __construct( $region ) {
        $this->regionModel = $region;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return $this->regionModel->get($name);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Account\Service\Account\Core\Instance\Region
     */
    public function set( $name, $value ) {
        $handler = 'set'.ucfirst($name);
        if ( method_exists($this, $handler) ) {
            $this->$handler($name, $value);
        } else {
            $this->regionModel->set($name, $value);
        }
        return $this;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Region
     */
    public function save() {
        $this->regionModel->save();
        return $this;
    }
}
<?php
namespace X\Module\Account\Service\Account\Core\Manager;
use X\Module\Account\Service\Account\Core\Model\AccountProfileModel;
/**
 * 
 */
class ProfileManager {
    /**
     * @var AccountProfileModel
     */
    private $profileModel = null;
    
    /**
     * @param AccountProfileModel $profileModel
     */
    public function __construct( $profileModel ) {
        $this->profileModel = $profileModel;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return $this->profileModel->get($name);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Account\Service\Account\Core\Manager\ProfileManager
     */
    public function set( $name, $value ) {
        $handler = 'set'.ucfirst($name);
        if ( method_exists($this, $handler) ) {
            $this->$handler($name, $value);
        } else {
            $this->profileModel->set($name, $value);
        }
        return $this;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ProfileManager
     */
    public function save() {
        $this->profileModel->save();
        return $this;
    }
    
    /**
     * @return array
     */
    public function toArray(){
        return $this->profileModel->toArray();
    }
    
    /**
     * @var integer
     */
    const SEX_MALE      = 1;
    /**
     * @var integer
     */
    const SEX_FEMALE    = 2;
}
<?php
namespace X\Module\Account\Service\Account\Core\Manager;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountConfigurationModel;
/**
 * 
 */
class ConfigurationManager {
    /**
     * @var string
     */
    private $accountID = null;
    
    /**
     * @var AccountConfigurationModel[]
     */
    private $configurations = null;
    
    /**
     * @param string $accountID
     */
    public function __construct( $accountID ) {
        $this->accountID = $accountID;
    }
    
    /**
     * @param string $type
     * @param string $name
     * @param string $value
     * @return \X\Module\Account\Service\Account\Core\Manager\ConfigurationManager
     */
    public function set( $type, $name, $value ) {
        $key = $this->generateIndexKey($type, $name);
        $configuration = $this->getConfiguraionModel($type, $name);
        if ( false === $configuration ) {
            $this->configurations[$key] = new AccountConfigurationModel();
            $this->configurations[$key]->account_id = $this->accountID;
        }
        
        /* @var $configuration AccountConfigurationModel */
        $configuration = $this->configurations[$key];
        $configuration->type = $type;
        $configuration->name = $name;
        $configuration->value = $value;
        $configuration->save();
        return $this;
    }
    
    /**
     * @param string $type
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get( $type, $name, $default=null ) {
        $configuration = $this->getConfiguraionModel($type, $name);
        if ( false === $configuration ) {
            return $default;
        }
        return $configuration->get('value');
    }
    
    /**
     * @param string $type
     * @param string $name
     * @return \X\Module\Account\Service\Account\Core\Manager\ConfigurationManager
     */
    public function remove( $type, $name ){
        $configuration = $this->getConfiguraionModel($type, $name);
        if ( false === $configuration ) {
            return $this;
        }
        
        $configuration->delete();
        $key = $this->generateIndexKey($type, $name);
        unset($this->configurations[$key]);
        return $this;
    }
    
    /**
     * @param string $type
     * @param string $name
     * @return boolean|\X\Module\Account\Service\Account\Core\Model\AccountConfigurationModel
     */
    private function getConfiguraionModel( $type, $name ) {
        $key = $this->generateIndexKey($type, $name);
        if ( isset($this->configurations[$key]) ) {
            return $this->configurations[$key];
        }
        
        $condition = array('type'=>$type, 'name'=>$name, 'account_id'=>$this->accountID);
        $configuration = AccountConfigurationModel::model()->find($condition);
        if ( null === $configuration ) {
            return false;
        }
        $this->configurations[$this->generateIndexKey($type, $name)] = $configuration;
        return $configuration;
    }
    
    /**
     * @param string $type
     * @param string $name
     * @return string
     */
    private function generateIndexKey( $type, $name ) {
        return $type.'_'.$name;
    }
}
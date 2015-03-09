<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Configuration;

/**
 * 
 */
use X\Module\Lunome\Model\System\SystemConfigurationModel;
use X\Module\Lunome\Util\Exception;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var unknown
     */
    protected static $serviceName = 'Configuration';
    
    /**
     * @param unknown $name
     */
    public function get( $name ) {
        $configurations = $this->getConfiguration();
        if ( !isset($configurations[$name]) ) {
            $attributes = array('name'=>$name);
            $configuration = SystemConfigurationModel::model()->find($attributes);
            if ( null === $configuration ) {
                throw new Exception('Configuration item "'.$name.'" does not exists.');
            }
            $configurations[$name] = $configuration;
        }
        return $configurations[$name]->value;
    }
    
    public function getAll() {
        return SystemConfigurationModel::model()->findAll();
    }
    
    public function update( $settings ) {
        foreach ( $settings as $key => $value ) {
            $config = SystemConfigurationModel::model()->find(array('name'=>$key));
            if ( null === $config ) {
                continue;
            }
            $config->value = $value;
            $config->save();
        }
    }
}
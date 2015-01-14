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
}
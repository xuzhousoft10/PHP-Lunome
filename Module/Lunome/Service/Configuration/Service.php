<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Configuration;

/**
 * 
 */
use X\Module\Lunome\Model\ConfigurationModel;
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
            $configuration = ConfigurationModel::model()->findByAttribute($attributes);
            if ( null === $configuration ) {
                throw new Exception('Configuration item "'.$name.'" does not exists.');
            }
            $configurations[$name] = $configuration;
        }
        return $configurations[$name]->value;
    }
}
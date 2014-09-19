<?php
/**
 * Namespace defination
 */
namespace X\Core\Service;

/**
 * The setting controller class for XService classes.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @since   Version 0.0.0
 * @version 0.0.0
 */
interface InterfaceSettingController {
    /**
     * Check whether the action exists by given name.
     * 
     * @param string $name The name of action to check.
     * @return boolean
     */
    public function hasAction( $name );
    
    /**
     * Execute the action of the controller
     * 
     * @param string $name The name of the action.
     * @param array $parameters The parameters to the action.
     * @return mixed
     */
    public function runAction( $name, $parameters );
    
    /**
     * Get the action name list of current controller.
     * 
     * @return string[]
     */
    public function getActions();
}

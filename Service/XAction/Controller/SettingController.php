<?php
/**
 * The setting controller of XAction service.
 */
namespace X\Service\XAction\Controller;

/**
 * Use statements
 */
use X\Core\X;

/**
 * This is the setting controller of XAction service.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 * 
 */
class SettingController extends \X\Core\Service\SettingController {
    /**
     * The action to create an xaction.
     * 
     * @param string $name The name of action
     * @param string $module The name of module that action belongs.
     * @return void
     */
    public function actionCreate( $name, $type, $module ) {
        $action = new Create();
        $action->run($name, $type, $module);
        
    }
}
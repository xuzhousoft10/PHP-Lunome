<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Controller;

/**
 * Use statements
 */
use X\Core\X;

/**
 * The admin controller.
 * 
 * @author  Michel Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XErrorServiceSettingController extends \X\Core\Service\SettingController {
    /**
     * Get setting page content.
     * 
     * @param string $config
     * @return string
     */
    public function adminActionSetting() {
        $config = parse_ini_file($this->getConfigPath('conf.ini'), true);
        $view = $this->getViewPath('setting');
        ob_start();
        ob_implicit_flush(false);
        require $view;
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Save the setting informations.
     * 
     * @param array $config
     */
    public function adminActionSaveSetting( $config ) {
        $conf = Ini::read($this->getConfigPath('conf.ini'), true);
        $conf->setData($config);
        $conf->save();
    }
}
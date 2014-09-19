<?php
/**
 * Namespace defination
 */
namespace X\Service\XDb;

/**
 * The setting controller for xsession service.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XDbServiceSettingController extends \X\Core\Service\SettingController {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getSettingHtml()
     */
    public function actionSetting() {
        $config = parse_ini_file($this->getConfigFilePath(), true);
        $ds = DIRECTORY_SEPARATOR;
        ob_start();
        ob_implicit_flush(false);
        require dirname(__FILE__).$ds.'setting'.$ds.'setting.php';
        $content = ob_get_clean();
        return $content;
    }
}
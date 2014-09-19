<?php
/**
 * Namespace defination
 */
namespace X\Service\XRequest;

/**
 * The setting controller for xurl service.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XRequestServiceSettingController extends \X\Core\Service\SettingController {
    /**
     * 
     * @return string
     */
    public function actionSetting() {
        $config = $this->config = parse_ini_file(dirname(__FILE__).DIRECTORY_SEPARATOR.'conf.ini', true);
        $view = sprintf('%s/core/views/setting.php', dirname(__FILE__));
        ob_start();
        ob_implicit_flush(false);
        require $view;
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * 
     * @param number $pos
     * @return string
     */
    public function actionHistory( $pos=0 ) {
        $pos = isset($_GET['pos']) ? $_GET['pos'] : 0;
        $parms = array(
                        'pos' => $pos,
                        'length' => $this->getHistoryNumberPerPage()
        );
        $histories = $this->service->getHistory($parms);
        $view = sprintf('%s/core/views/history.php', dirname(__FILE__));
        ob_start();
        ob_implicit_flush(false);
        require $view;
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * The number of records will be display on page.
     *
     * @var integer
     */
    protected function getHistoryNumberPerPage() {
        return 10;
    }
}
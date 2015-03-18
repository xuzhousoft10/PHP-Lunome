<?php
namespace X\Module\Account\Action\Setting;
/**
 *
 */
use X\Module\Account\Util\Action\UserSetting;
/**
 * 
 */
class Sns extends UserSetting { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $config=null ) {
        $account = $this->getCurrentAccount();
        $configurationManager = $account->getConfigurationManager();
        $configurationType = 'sns';
        
        $view = $this->getView();
        if ( !empty($config) && is_array($config)) {
            foreach ( $config as $name => $value ) {
                $configurationManager->set($configurationType, $name, $value);
            }
        }
        
        $configurations = array();
        $configurations['auto_share'] = $configurationManager->get($configurationType, 'auto_share', '0');
        $name   = 'USER_SETTING_SNS';
        $path   = $this->getParticleViewPath('Setting/SNS');
        $option = array();
        $data   = array('configurations'=>$configurations);
        $this->loadParticle($name, $path, $option, $data);
        
        $view->title = '社交平台设置';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\UserSetting::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::SETTING_ITEM_SNS;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        
        $this->addCssLink('Bootstrap-Switch', $assetsURL.'/library/bootstrap/plugin/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css');
        $this->addScriptFile('Bootstrap-Switch', $assetsURL.'/library/bootstrap/plugin/bootstrap-switch/js/bootstrap-switch.min.js');
        
        $switchOption = json_encode(array('size'=>'mini', 'onText'=>'已开启', 'offText'=>'已关闭'));
        $this->getView()->getScriptManager()->addString('Bootstrap-Switch-Enable', '$(document).ready(function(){$("#sns-auto-share").bootstrapSwitch('.$switchOption.');});');
    }
}
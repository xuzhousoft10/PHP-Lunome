<?php
/**
 * The action file for user/logout action.
 */
namespace X\Module\Lunome\Action\User\Setting;

/**
 *
 */
use X\Module\Lunome\Util\Action\UserSetting;

/**
 * The action class for user/logout action.
 * @author Unknown
 */
class Information extends UserSetting { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $information=array() ) {
        $accountManager = $this->getUserService()->getAccount();
        $account = $accountManager->getInformation();
        $view = $this->getView();
        
        if ( !empty($information) && is_array($information)) {
            $account = $accountManager->updateInformation($information);
        }
        
        $name   = 'USER_SETTING_INFRMATION';
        $path   = $this->getParticleViewPath('User/Setting/Information');
        $option = array();
        $data   = array('account'=>$account);
        $this->loadParticle($name, $path, $option, $data);
        $this->setDataToParticle($name, 'sexMap', $accountManager->getSexNames());
        $this->setDataToParticle($name, 'sexualityMap', $accountManager->getSexualityNames());
        $this->setDataToParticle($name, 'emotionMap', $accountManager->getEmotionStatuNames());
        $view->title = '个人信息';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\UserSetting::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::SETTING_ITEM_INFO;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->addCssLink('Bootstrap-Date-Picker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/css/datepicker3.css'); 
        $this->addScriptFile('Bootstrap-Date-Picker', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js'); 
        $this->addScriptFile('Bootstrap-Date-Picker-Language', $assetsURL.'/library/bootstrap/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js'); 
        $this->addScriptFile('User-Setting-Information', $assetsURL.'/js/user/information.js'); 
    }
}
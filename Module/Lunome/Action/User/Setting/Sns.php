<?php
/**
 * The action file for user/logout action.
 */
namespace X\Module\Lunome\Action\User\Setting;

/**
 *
 */
use X\Module\Lunome\Util\Action\UserSetting;
use X\Module\Lunome\Service\User\Account;

/**
 * The action class for user/logout action.
 * @author Unknown
 */
class Sns extends UserSetting { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $config ) {
        $account = $this->getUserService()->getAccount();
        $configurations = array();
        $configurations['auto_share'] = $account->getConfiguration(Account::SETTING_TYPE_SNS, 'auto_share', '1');
        
        if ( !empty($config) ) {
            $configurations = array_merge($configurations, $config);
            $account->setConfigurations(Account::SETTING_TYPE_SNS, $configurations);
        }
        
        $name   = 'USER_SETTING_SNS';
        $path   = $this->getParticleViewPath('User/Setting/SNS');
        $option = array();
        $data   = array('configurations'=>$configurations);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = '社交平台设置';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\UserSetting::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::SETTING_ITEM_SNS;
    }
}
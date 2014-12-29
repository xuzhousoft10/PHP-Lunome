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
        
        if ( !empty($information) ) {
            $account = $accountManager->updateInformation($information);
        }
        
        $name   = 'USER_SETTING_INFRMATION';
        $path   = $this->getParticleViewPath('User/Setting/Information');
        $option = array();
        $data   = array('account'=>$account);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = '个人信息';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\UserSetting::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::SETTING_ITEM_INFO;
    }
}
<?php
namespace X\Module\Account\Action\Setting;
/**
 *
 */
use X\Module\Account\Util\Action\UserSetting;
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
        $account = $this->getCurrentAccount();
        $profile = $account->getProfileManager();
        
        $view = $this->getView();
        if ( !empty($information) && is_array($information)) {
            foreach ( $information as $name => $value ) {
                $profile->set($name, $value);
            }
            $profile->save();
        }
        
        $name   = 'USER_SETTING_INFRMATION';
        $path   = $this->getParticleViewPath('Setting/Information');
        $option = array();
        $data   = array('account'=>$profile);
        $this->loadParticle($name, $path, $option, $data);
        $this->setDataToParticle($name, 'sexMap', $this->getSexNames());
        $this->setDataToParticle($name, 'sexualityMap', $this->getSexualityNames());
        $this->setDataToParticle($name, 'emotionMap', $this->getEmotionStatuNames());
        $view->title = '个人信息';
    }
    
    /**
     * @return array
     */
    public function getSexNames() {
        return array(0=>'保密',1=>'男',2=>'女',3=>'其他');
    }
    
    /**
     * @return array
     */
    public function getSexualityNames() {
        return array(0=>'保密',1=>'异性',2=>'同性',3=>'双性',4=>'无性',5=>'二禁');
    }
    
    /**
     * @return array
     */
    public function getEmotionStatuNames() {
        return array(0=>'保密',1=>'单身',2=>'热恋中',3=>'同居',4=>'已订婚',5=>'已婚',6=>'分居',7=>'离异',8=>'很难说',9=>'其他');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\UserSetting::getActiveSettingItem()
     */
    protected function getActiveSettingItem() {
        return self::SETTING_ITEM_INFO;
    }
}
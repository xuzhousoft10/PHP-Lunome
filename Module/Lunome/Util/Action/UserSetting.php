<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Core\X;

/**
 * 该Action是当用户直接登录后进入的用户主页的Action基类。
 * 该类加载了一个布局， 以及维护一个用户菜单。
 */
abstract class UserSetting extends VisualMain {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        $this->initSettingItems();
        $this->activeSettingItem($this->getActiveSettingItem());
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::afterRunAction()
     */
    protected function afterRunAction() {
        $this->activeMenuItem(self::MENU_ITEM_SETTING);
        
        $name   = 'USER_SETTING_MENU';
        $path   = $this->getParticleViewPath('User/SettingMenu');
        $option = array();
        $data   = array('settingItems'=>$this->settingItems);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        parent::afterRunAction();
    }
    
    /**
     * @var unknown
     */
    private $settingItems = array();
    
    /**
     * @return void
     */
    private function initSettingItems () {
        $items = array();
        
        $items[self::SETTING_ITEM_INFO] = array();
        $items[self::SETTING_ITEM_INFO]['label']     = '个人信息';
        $items[self::SETTING_ITEM_INFO]['isActive']  = false;
        $items[self::SETTING_ITEM_INFO]['link']      = '/?module=lunome&action=user/setting/information';
        
        $items[self::SETTING_ITEM_SNS] = array();
        $items[self::SETTING_ITEM_SNS]['label']     = '社交平台';
        $items[self::SETTING_ITEM_SNS]['isActive']  = false;
        $items[self::SETTING_ITEM_SNS]['link']      = '/?module=lunome&action=user/setting/sns';
        
        $this->settingItems = $items;
    }
    
    /**
     * @param unknown $name
     */
    public function activeSettingItem( $name ) {
        foreach ( $this->settingItems as $itemName => $item ) {
            $this->settingItems[$itemName]['isActive'] = false;
        }
        $this->settingItems[$name]['isActive'] = true;
    }
    
    /**
     * @var unknown
     */
    const SETTING_ITEM_INFO = 'information';
    const SETTING_ITEM_SNS = 'sns';
    
    /**
     * 
     */
    abstract protected function getActiveSettingItem();
}
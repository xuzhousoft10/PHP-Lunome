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
abstract class FriendManagement extends VisualMain {
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
        $this->activeMenuItem(self::MENU_ITEM_FRIEND);
        
        $name   = 'FRIEND_MANAGEMENT_MENU';
        $path   = $this->getParticleViewPath('User/Friend/Menu');
        $option = array();
        $data   = array('settingItems'=>$this->settingItems);
        $this->loadParticle($name, $path, $option, $data);
        
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
        
        $items[self::FRIEND_MENU_ITEM_LIST] = array();
        $items[self::FRIEND_MENU_ITEM_LIST]['label']     = '所有好友';
        $items[self::FRIEND_MENU_ITEM_LIST]['isActive']  = false;
        $items[self::FRIEND_MENU_ITEM_LIST]['link']      = '/?module=lunome&action=user/friend/index';
        
        $items[self::FRIEND_MENU_ITEM_SEARCH] = array();
        $items[self::FRIEND_MENU_ITEM_SEARCH]['label']     = '寻找好友';
        $items[self::FRIEND_MENU_ITEM_SEARCH]['isActive']  = false;
        $items[self::FRIEND_MENU_ITEM_SEARCH]['link']      = '/?module=lunome&action=user/friend/search';
        
        $items[self::FRIEND_MENU_ITEM_INTERACTION] = array();
        $items[self::FRIEND_MENU_ITEM_INTERACTION]['label']     = '集体互动';
        $items[self::FRIEND_MENU_ITEM_INTERACTION]['isActive']  = false;
        $items[self::FRIEND_MENU_ITEM_INTERACTION]['link']      = '/?module=lunome&action=user/friend/interaction';
        
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
    const FRIEND_MENU_ITEM_LIST = 'list';
    const FRIEND_MENU_ITEM_SEARCH = 'search';
    const FRIEND_MENU_ITEM_INTERACTION = 'interaction';
    
    /**
     * 
     */
    abstract protected function getActiveSettingItem();
}
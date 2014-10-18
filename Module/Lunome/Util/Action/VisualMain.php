<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * 该Action是当用户直接登录后进入的用户主页的Action基类。
 * 该类加载了一个布局， 以及维护一个用户菜单。
 */
abstract class VisualMain extends Visual {
    /**
     * The main menu items.
     * @var unknown
     */
    protected $menuItems = array();
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        $isGuest = $this->getService(UserService::getServiceName())->getIsGuest();
        if ( $isGuest ) {
            $this->gotoURL('/index.php?module=lunome&action=user/login/index');
        }
        
        parent::beforeRunAction();
        
        $layout = $this->getLayoutViewPath('Main');
        $this->getView()->loadLayout($layout);
        $this->getView()->addData('user', $this->getCurrentUserData());
        $this->initMainMenuItems();
    }
    
    protected function afterRunAction() {
        $this->getView()->addData('mainMenu', $this->menuItems);
        parent::afterRunAction();
    }
    
    public function activeMenuItem( $name ) {
        foreach ( $this->menuItems as $itemName => $item ) {
            $this->menuItems[$itemName]['isActive'] = false;
        }
        $this->menuItems[$name]['isActive'] = true;
    }
    
    protected function initMainMenuItems() {
        $items[self::MENU_ITEM_MOVIE] = array();
        $items[self::MENU_ITEM_MOVIE]['label']       = '电影';
        $items[self::MENU_ITEM_MOVIE]['isActive']    = false;
        $items[self::MENU_ITEM_MOVIE]['link']        = '/?module=lunome&action=movie/index';
        
        $items[self::MENU_ITEM_TV] = array();
        $items[self::MENU_ITEM_TV]['label']         = '电视';
        $items[self::MENU_ITEM_TV]['isActive']      = false;
        $items[self::MENU_ITEM_TV]['link']          = '/?module=lunome&action=tv/index';
        
        $items[self::MENU_ITEM_COMIC] = array();
        $items[self::MENU_ITEM_COMIC]['label']       = '动漫';
        $items[self::MENU_ITEM_COMIC]['isActive']    = false;
        $items[self::MENU_ITEM_COMIC]['link']        = '/?module=lunome&action=comic/index';
        
        $items[self::MENU_ITEM_BOOK] = array();
        $items[self::MENU_ITEM_BOOK]['label']       = '图书';
        $items[self::MENU_ITEM_BOOK]['isActive']    = false;
        $items[self::MENU_ITEM_BOOK]['link']        = '/?module=lunome&action=book/index';
        
        $items[self::MENU_ITEM_GAME] = array();
        $items[self::MENU_ITEM_GAME]['label']       = '游戏';
        $items[self::MENU_ITEM_GAME]['isActive']    = false;
        $items[self::MENU_ITEM_GAME]['link']        = '/?module=lunome&action=game/index';
        
        /*
        $items[self::MENU_ITEM_FRIEND] = array();
        $items[self::MENU_ITEM_FRIEND]['label']       = '好友';
        $items[self::MENU_ITEM_FRIEND]['isActive']    = false;
        $items[self::MENU_ITEM_FRIEND]['link']        = '/?module=lunome&action=user/friend/index';
        */
        
        $this->menuItems = $items;
    }
    
    const MENU_ITEM_MOVIE   = 'movie';
    const MENU_ITEM_TV      = 'tv';
    const MENU_ITEM_BOOK    = 'book';
    const MENU_ITEM_GAME    = 'game';
    const MENU_ITEM_COMIC   = 'comic';
    const MENU_ITEM_FRIEND  = 'friend';
}
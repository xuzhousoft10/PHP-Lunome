<?php
/**
 *
 */
namespace X\Module\Smartphone\Action\System\Menu;

/**
 *
 */
use X\Module\Smartphone\Util\Action\Menu;

/**
 *
 */
class Index extends Menu {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Smartphone\Util\Action\Menu::getMenu()
     */
    protected function getMenu() {
        $menu = array();
        $menu[] = array('label'=>'电影', 'link'=>'/?module=smartphone&action=movie/menu/index');
        return $menu;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Smartphone\Util\Action\Menu::getActiveNavItem()
     */
    protected function getActiveNavItem() {
        return self::NAV_MENU_SYSTEM;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Smartphone\Util\Action\Menu::getReturnURL()
     */
    protected function getReturnURL() {
        return '/?module=smartphone';
    }
}
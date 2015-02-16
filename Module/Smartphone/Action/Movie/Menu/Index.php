<?php
/**
 *
 */
namespace X\Module\Smartphone\Action\Movie\Menu;

/**
 *
 */
use X\Module\Smartphone\Util\Action\Menu;
use X\Module\Lunome\Service\Movie\Service as MovieService;

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
        $menu[] = array('label'=>'未标记', 'link'=>'/?module=smartphone&action=movie/index&mark='.MovieService::MARK_UNMARKED);
        $menu[] = array('label'=>'想看', 'link'=>'/?module=smartphone&action=movie/index&mark='.MovieService::MARK_INTERESTED);
        $menu[] = array('label'=>'已看', 'link'=>'/?module=smartphone&action=movie/index&mark='.MovieService::MARK_WATCHED);
        $menu[] = array('label'=>'忽略', 'link'=>'/?module=smartphone&action=movie/index&mark='.MovieService::MARK_IGNORED);
        $menu[] = array('label'=>'排行', 'link'=>'/?module=smartphone&action=movie/top');
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
        return '/?module=smartphone&action=system/menu/index';
    }
}
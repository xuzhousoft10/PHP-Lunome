<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action;

/**
 * 
 */
abstract class Visual extends \X\Util\Action\Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        /* Load index layout. */
        $this->getView()->loadLayout($this->getLayoutViewPath('Index'));
        $this->initMenu();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->addData('menu', $this->menu);
        $this->getView()->addData('activeMenuItem', $this->activeMenuItem);
        parent::afterRunAction();
    }
    
    /**
     * 
     * @var unknown
     */
    private $menu = array();
    private $activeMenuItem = null;
    
    /**
     * 
     */
    private function initMenu() {
        $this->menu[self::MENU_ACCOUNT_MANAGEMENT]  = array('name'=>'会员帐号管理', 'link'=>'/index.php?module=backend&action=account/index');
        $this->menu[self::MENU_MOVIE_MANAGEMENT]    = array('name'=>'电影资源管理', 'link'=>'/index.php?module=backend&action=movie/index');
        $this->menu[self::MENU_TV_MANAGEMENT]       = array('name'=>'电视资源管理', 'link'=>'/index.php?module=backend&action=tv/index');
        $this->menu[self::MENU_COMIC_MANAGEMENT]    = array('name'=>'动漫资源管理', 'link'=>'/index.php?module=backend&action=comic/index');
        $this->menu[self::MENU_BOOK_MANAGEMENT]     = array('name'=>'书籍资源管理', 'link'=>'/index.php?module=backend&action=tv/index');
        $this->menu[self::MENU_GAME_MANAGEMENT]     = array('name'=>'游戏资源管理', 'link'=>'/index.php?module=backend&action=comic/index');
    }
    
    /**
     * 
     * @param unknown $item
     */
    protected function setActiveItem( $item ) {
        $this->activeMenuItem = $item;
    }
    
    /* Menu items */
    const MENU_ACCOUNT_MANAGEMENT   = 'account_management';
    const MENU_MOVIE_MANAGEMENT     = 'movie_management';
    const MENU_TV_MANAGEMENT        = 'tv_management';
    const MENU_COMIC_MANAGEMENT     = 'comic_management';
    const MENU_BOOK_MANAGEMENT      = 'book_management';
    const MENU_GAME_MANAGEMENT      = 'game_management';
}
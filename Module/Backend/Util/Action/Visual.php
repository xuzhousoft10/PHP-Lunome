<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Backend\Util\Action;

/**
 * 
 */
use X\Core\X;

/**
 * Visual action class
 * 
 * @method \X\Module\Lunome\Service\User\Service getUserService()
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @method \X\Module\Lunome\Service\Tv\Service getTvService()
 */
abstract class Visual extends \X\Util\Action\Visual {
    /**
     * @var unknown
     */
    protected $menuItems = array();
    
    /**
     * @var unknown
     */
    protected $menuItemActived = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeRunAction()
     */
    public function beforeRunAction() {
        parent::beforeRunAction();
        $this->menuItems[self::MENU_ITEM_DEFAULT]   = array(
            'name' => 'Home',
            'link' => '/?module=backend',
        );
        $this->menuItems[self::MENU_ITEM_ACCOUNT]   = array(
            'name' => '用户管理',
            'subitem' => array(
                array('name'=>'所有用户','link'=>'/?module=backend&action=account/index'),
            ),
        );
        $this->menuItems[self::MENU_ITEM_MOVIE]     = array(
            'name' => '电影管理',
            'subitem' => array(
                array('name'=>'电影','link'=>'#'),
                array('name'=>'添加电影','link'=>'#'),
                array('name'=>'分类','link'=>'#'),
                array('name'=>'添加分类','link'=>'#'),
            ),
        );
        $this->menuItems[self::MENU_ITEM_REGION]    = array(
            'name' => '区域管理',
            'subitem' => array(
                array('name'=>'区域','link'=>'#'),
                array('name'=>'添加区域','link'=>'#'),
            ),
        );
        $this->menuItems[self::MENU_ITEM_PEOPLE]    = array(
            'name' => '人物管理',
            'subitem' => array(
                array('name'=>'人物','link'=>'#'),
                array('name'=>'添加人物','link'=>'#'),
            ),
        );
        $this->menuItems[self::MENU_ITEM_SYSTEM]    = array(
            'name' => '系统管理',
            'subitem' => array(
                array('name'=>'状态','link'=>'#'),
                array('name'=>'配置','link'=>'#'),
                array('name'=>'日志','link'=>'#'),
                array('name'=>'备份','link'=>'#'),
                array('name'=>'更新','link'=>'#'),
            ),
        );
         
        $view = $this->getView();
        $layoutPath = $this->getLayoutViewPath('Main');
        $view->loadLayout($layoutPath);
        $view->setData('mainMenu', $this->menuItems);
        
        $view->setFavicon($this->getAssetsURL().'/image/favicon.ico');
    }
    
    /**
     * @param unknown $item
     */
    public function setMenuItemActived( $item ) {
        $this->menuItemActived = $item;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    public function beforeDisplay() {
        $view = $this->getView();
        $assetsURL = $this->getAssetsURL();
        $backendAssetsURL = $this->getBackendAssetsURL();
        $view->addData('backendAssetsURL', $backendAssetsURL);
        $view->setData('mainMenuActived', $this->menuItemActived);
        
        $assetsURL = $this->getAssetsURL();
        $this->getView()->addCssLink('bootstrap',       $assetsURL.'/library/bootstrap/css/bootstrap.min.css');
        $this->getView()->addCssLink('bootstrap-theme', $assetsURL.'/library/bootstrap/theme/slate.min.css');
        $this->getView()->addCssLink('bootstrap-ext',   $assetsURL.'/css/bootstrap-ext.css');
        $this->getView()->addScriptFile('jquery',       $assetsURL.'/library/jquery/jquery-1.11.1.min.js');
        $this->getView()->addScriptFile('bootstrap',    $assetsURL.'/library/bootstrap/js/bootstrap.min.js');
        
        parent::beforeDisplay();
    }
    
    /**
     * @param unknown $title
     */
    public function setPageTitle( $title ) {
        $this->getView()->title = $title.' | Lunome后台编辑';
    }
    
    /**
     * @return string
     */
    public function getBackendAssetsURL() {
        $url = $this->getAssetsURL();
        return $url.'/backend';
    }
    
    const MENU_ITEM_DEFAULT = 'default';
    const MENU_ITEM_ACCOUNT = 'account';
    const MENU_ITEM_MOVIE   = 'movie';
    const MENU_ITEM_PEOPLE  = 'people';
    const MENU_ITEM_REGION  = 'region';
    const MENU_ITEM_SYSTEM  = 'system';
}
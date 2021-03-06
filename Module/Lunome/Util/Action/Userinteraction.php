<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Module\Lunome\Util\Action\VisualMain;
use X\Module\Account\Module as AccountModule;

/**
 * 
 */
abstract class Userinteraction extends VisualMain {
    /**
     * @var unknown
     */
    protected $interactionMenu = array();
    
    /**
     * @var unknown
     */
    private $activeInteractionMenuItem = null;
    
    /**
     * @var unknown
     */
    public $interactionMenuParams = array();
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        $this->initInteractionMenu();
        $this->activeMenuItem(self::MENU_ITEM_FRIEND);
    }
    
    /**
     * 
     */
    private function initInteractionMenu() {
        $this->interactionMenu = array();
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INDEX]['label'] = '互动主页';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INDEX]['link'] = '/?module=account&action=interaction/index';
        
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE]['label'] = '想请TA看场电影';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE]['link'] = '/?module=movie&action=interaction/inviteToWatchMovie';
        
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_GET_TOPIC]['label'] = '想与TA找点话题';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_GET_TOPIC]['link'] = '/?module=movie&action=interaction/findTopic';
        
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_INDEX);
    }
    
    /**
     * @param unknown $item
     */
    public function setActiveInteractionMenuItem( $item ) {
        $this->activeInteractionMenuItem = $item;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::afterRunAction()
     */
    protected function afterRunAction() {
        $name   = 'USER_INTERACTION_MENU';
        $path   = $this->getParticleViewPath('Interaction/InteractionMenu', AccountModule::getModuleName());
        $option = array();
        $data   = array(
            'items'         => $this->interactionMenu, 
            'activeItem'    => $this->activeInteractionMenuItem, 
            'parameters'    => empty($this->interactionMenuParams) ? '' : '&'.http_build_query($this->interactionMenuParams),
        );
        $this->loadParticle($name, $path, $option, $data);
        parent::afterRunAction();
    }
    
    const INTERACTION_MENU_ITEM_INDEX = 'index';
    const INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE = 'movie';
    const INTERACTION_MENU_ITEM_GET_TOPIC = 'topic';
}
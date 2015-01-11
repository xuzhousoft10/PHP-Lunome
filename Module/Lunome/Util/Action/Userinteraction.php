<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Action;

/**
 * 
 */
use X\Module\Lunome\Util\Action\VisualMain;

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
     * @var \X\Module\Lunome\Service\User\Service
     */
    protected $userService = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        $this->initInteractionMenu();
        $this->userService = $this->getUserService();
        $this->activeMenuItem(self::MENU_ITEM_FRIEND);
    }
    
    /**
     * 
     */
    private function initInteractionMenu() {
        $this->interactionMenu = array();
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INDEX]['label'] = '互动主页';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INDEX]['link'] = '/?module=lunome&action=user/interaction/index';
        
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE]['label'] = '想请TA看场电影';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE]['link'] = '/?module=lunome&action=user/interaction/inviteToWatchMovie';
        
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_GET_TOPIC]['label'] = '想与TA找点话题';
        $this->interactionMenu[self::INTERACTION_MENU_ITEM_GET_TOPIC]['link'] = '/?module=lunome&action=user/interaction/findTopic';
        
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
        $path   = $this->getParticleViewPath('User/Interaction/InteractionMenu');
        $option = array();
        $data   = array(
            'items'         => $this->interactionMenu, 
            'activeItem'    => $this->activeInteractionMenuItem, 
            'parameters'    => $this->interactionMenuParams,
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        parent::afterRunAction();
    }
    
    const INTERACTION_MENU_ITEM_INDEX = 'index';
    const INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE = 'movie';
    const INTERACTION_MENU_ITEM_GET_TOPIC = 'topic';
}
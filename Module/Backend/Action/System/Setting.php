<?php
/**
 * 
 */
namespace X\Module\Backend\Action\System;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\Configuration\Service as ConfigService;

/**
 * 
 */
class Setting extends Visual {
    /**
     * 
     */
    public function runAction( $settings=null ) {
        $view = $this->getView();
        /* @var $configService ConfigService  */
        $configService = X::system()->getServiceManager()->get(ConfigService::getServiceName());
        if ( !empty($settings) && is_array($settings) ) {
            $configService->update($settings);
        }
        
        $configurations = $configService->getAll();
        
        $viewName = 'BACKEND_CONFIG_INDEX';
        $viewPath = $this->getParticleViewPath('System/Settings');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'configurations', $configurations);
        
        $this->setPageTitle('系统配置');
        $this->setMenuItemActived(self::MENU_ITEM_SYSTEM);
    }
}
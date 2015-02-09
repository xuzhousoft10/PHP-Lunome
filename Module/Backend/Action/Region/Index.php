<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Region;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\Region\Service as RegionService;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction($parent=null, $page=1) {
        $view = $this->getView();
        /* @var $regionService RegionService */
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        $regions = $regionService->getAll($parent);
        $parent = empty($parent) ? null : $regionService->get($parent);
        
        $viewName = 'BACKEND_REGION_INDEX';
        $viewPath = $this->getParticleViewPath('Region/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'regions', $regions);
        $view->setDataToParticle($viewName, 'parent', $parent);
        
        $this->setPageTitle('区域管理');
        $this->setMenuItemActived(self::MENU_ITEM_REGION);
    }
}
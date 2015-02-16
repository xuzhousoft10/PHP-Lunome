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
class Edit extends Visual {
    /**
     * 
     */
    public function runAction($id=null, $parent=null, $region=null) {
        $view = $this->getView();
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        $parent = empty($parent) ? null : $regionService->get($parent);
        $level = empty($parent) ? 1 : $parent->level + 1;
        
        if ( !empty( $region ) ) {
            if ( empty($id) ) {
                $region = $regionService->addRegion($region)->toArray();
            } else {
                $region = $regionService->updateRegion($id, $region)->toArray();
            }
            $this->gotoURL('/?module=backend&action=region/index', array('parent'=>$region['parent']));
        } else {
            if ( empty($id) ) {
                $region = array('name'=>'', 'parent'=>$parent, 'id'=>null, 'level'=>$level);
            } else {
                $region = $regionService->get($id)->toArray();
                $parent = $regionService->get($region['parent']);
            }
        }
        
        $viewName = 'BACKEND_REGION_EDIT';
        $viewPath = $this->getParticleViewPath('Region/Edit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'region', $region);
        $view->setDataToParticle($viewName, 'parent', $parent);
        
        $this->setPageTitle('编辑区域');
        $this->setMenuItemActived(self::MENU_ITEM_REGION);
    }
}
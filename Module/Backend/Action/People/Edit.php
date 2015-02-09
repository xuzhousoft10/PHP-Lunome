<?php
/**
 * 
 */
namespace X\Module\Backend\Action\People;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Service\People\Service as PeopleService;

/**
 * 
 */
class Edit extends Visual {
    /**
     * 
     */
    public function runAction($id=null, $people=null) {
        $view = $this->getView();
        $peopleService = X::system()->getServiceManager()->get(PeopleService::getServiceName());
        
        if ( !empty( $people ) ) {
            if ( empty($id) ) {
                $people = $peopleService->add($people)->toArray();
            } else {
                $people = $peopleService->update($id, $people)->toArray();
            }
            $this->gotoURL('/?module=backend&action=people/index');
        } else {
            if ( empty($id) ) {
                $people = array('name'=>'', 'id'=>null);
            } else {
                $people = $peopleService->get($id)->toArray();
            }
        }
        
        $viewName = 'BACKEND_PEOPLE_EDIT';
        $viewPath = $this->getParticleViewPath('People/Edit');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'people', $people);
        
        $this->setPageTitle('编辑人物');
        $this->setMenuItemActived(self::MENU_ITEM_PEOPLE);
    }
}
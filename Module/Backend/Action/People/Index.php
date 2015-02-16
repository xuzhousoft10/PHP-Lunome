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
class Index extends Visual {
    /**
     * 
     */
    public function runAction($page=1) {
        $view = $this->getView();
        $poepleService = X::system()->getServiceManager()->get(PeopleService::getServiceName());
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('people_index_page_size');
        $page = (int)$page;
        $people = $poepleService->getAll(($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_PEOPLE_INDEX';
        $viewPath = $this->getParticleViewPath('People/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'people', $people);
        
        $viewName = 'BACKEND_PEOPLE_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $totalCount = $poepleService->count();
        $view->setDataToParticle($viewName, 'totalCount', $totalCount);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array('module'=>'backend', 'action'=>'people/index');
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('人物管理');
        $this->setMenuItemActived(self::MENU_ITEM_PEOPLE);
    }
}
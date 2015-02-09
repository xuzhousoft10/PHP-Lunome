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
use X\Service\XLog\Service as LogService;

/**
 * 
 */
class Log extends Visual {
    /**
     * 
     */
    public function runAction($page=1) {
        $view = $this->getView();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('log_index_page_size');
        $page = (int)$page;
        $columns = $moduleConfig->get('log_index_display_columns');
        
        /* @var $logService LogService */
        $logService = X::system()->getServiceManager()->get(LogService::getServiceName());
        $logs = $logService->getLogs(($page-1)*$pageSize, $pageSize);
        
        $viewName = 'BACKEND_LOG_INDEX';
        $viewPath = $this->getParticleViewPath('System/Logs');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'logs', $logs);
        $view->setDataToParticle($viewName, 'columns', $columns);
        
        $viewName = 'BACKEND_LOG_INDEX_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'totalCount', false);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $pagerParams = array('module'=>'backend', 'action'=>'system/log');
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pagerParams));
        
        $this->setPageTitle('日志管理');
        $this->setMenuItemActived(self::MENU_ITEM_SYSTEM);
    }
}
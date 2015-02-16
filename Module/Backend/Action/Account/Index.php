<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Account;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual;
use X\Module\Lunome\Model\Account\AccountModel;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction($condition=null, $page=1, $role=null) {
        $view = $this->getView();
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = (int)($moduleConfig->get('account_index_page_size'));
        $page = (int)$page;
        $condition = (empty($condition) || !is_array($condition)) ? array() : $condition;
        $condition = array_filter($condition);
        if ( !empty($role) ) {
            $condition['role'] = (int)$role;
        }
        $dataPosition = ($page-1)*$pageSize;
        
        $accountStatusNames = array(
            AccountModel::ST_FREEZE     => '已冻结',
            AccountModel::ST_IN_USE     => '正常',
            AccountModel::ST_NOT_USED   => '未使用',
        );
        
        $accounts = $accountManager->findAll($condition, $dataPosition, $pageSize);
        foreach ( $accounts as $index => $account ) {
            $role = array(
                array(
                    'name'=>'编辑', 
                    'hasRole'=>$userService->isEditor($account['role']), 
                    'code'=>AccountModel::RL_EDITOR_ACCOUNT),
                array(
                    'name'=>'管理', 
                    'hasRole'=>$userService->isEditor($account['role']), 
                    'code'=>AccountModel::RL_MANAGEMENT_ACCOUNT),
            );
            $accounts[$index]['role']=$role;
            
            $account['status'] = (int)$account['status'];
            $accounts[$index]['status'] = $accountStatusNames[$account['status']];
        }
        
        $viewName = 'BACKEND_ACCOUNT_INDEX';
        $viewPath = $this->getParticleViewPath('Account/Index');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'accounts', $accounts);
        $view->setDataToParticle($viewName, 'statusNames', $accountStatusNames);
        $view->setDataToParticle($viewName, 'condition', $condition);
        
        $viewName = 'BACKEND_ACCOUNT_PAGER';
        $viewPath = $this->getParticleViewPath('Util/Pager');
        $view->loadParticle($viewName, $viewPath);
        $accountCount = $accountManager->count($condition);
        $view->setDataToParticle($viewName, 'totalCount', $accountCount);
        $view->setDataToParticle($viewName, 'currentPage', $page);
        $view->setDataToParticle($viewName, 'pageSize', $pageSize);
        $pageParams = array_merge(array('module'=>'backend', 'action'=>'account/index'), array('condition'=>$condition));
        $view->setDataToParticle($viewName, 'parameters', http_build_query($pageParams));
        
        $this->setPageTitle('用户管理');
        $this->setMenuItemActived(self::MENU_ITEM_ACCOUNT);
    }
}
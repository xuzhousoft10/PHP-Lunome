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
class Detail extends Visual {
    /**
     * 
     */
    public function runAction($account) {
        $view = $this->getView();
        $userService = $this->getUserService();
        $accountManager = $userService->getAccount();
        
        if ( !$accountManager->has($account) ) {
            $this->throw404();
        }
        
        $account = $accountManager->get($account)->toArray();
        $accountStatus = (int)$account['status'];
        switch ( $accountStatus ) {
        case AccountModel::ST_NOT_USED : 
            $account['status'] = array();
            $account['status']['name'] = '未使用';
            $account['status']['actions'] = array();
            break;
        case AccountModel::ST_IN_USE :
            $account['status'] = array();
            $account['status']['name'] = '正常';
            $account['status']['actions'] = array(
                AccountModel::ST_FREEZE => '冻结',
            );
            break;
        case AccountModel::ST_FREEZE :
            $account['status'] = array();
            $account['status']['name'] = '已冻结';
            $account['status']['actions'] = array(
                AccountModel::ST_IN_USE => '恢复正常',
            );
            break;
        default:
            $account['status'] = array();
            $account['status']['name'] = '未知';
            $account['status']['actions'] = array(
                AccountModel::ST_IN_USE => '恢复正常',
            );
            break;
        }
        
        $role = (int)$account['role'];
        $account['role'] = array();
        if ( AccountModel::ST_NOT_USED !== $accountStatus ) {
            $account['role'][AccountModel::RL_NORMAL_ACCOUNT] = array('name'=>'访问', 'hasRole'=>true);
            $account['role'][AccountModel::RL_EDITOR_ACCOUNT] = array('name'=>'编辑', 'hasRole'=>false);
            $account['role'][AccountModel::RL_MANAGEMENT_ACCOUNT] = array('name'=>'管理', 'hasRole'=>false);
            if ( $userService->isEditor($role) ) {
                $account['role'][AccountModel::RL_EDITOR_ACCOUNT]['hasRole'] = true;
            }
            if ( $userService->isManager($role) ) {
                $account['role'][AccountModel::RL_MANAGEMENT_ACCOUNT]['hasRole'] = true;
            }
        }
        
        $viewName = 'BACKEND_ACCOUNT_DETAIL';
        $viewPath = $this->getParticleViewPath('Account/Detail');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'account', $account);
        
        $configurations = $accountManager->getConfigurationsByAccountId($account['id']);
        $view->setDataToParticle($viewName, 'accountConfigurations', $configurations);
        
        $this->setMenuItemActived(self::MENU_ITEM_ACCOUNT);
        $this->setPageTitle('帐号“'.$account['account'].'”详情');
    }
}
<?php
namespace X\Util\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * The basic action class
 * @author Michael Luthor <michaelluthor@163.com>
 */
abstract class Basic extends \X\Service\XAction\Core\Handler\WebAction {
    /**
     * Get the module that this action belongs to.
     * @return \X\Core\Module\XModule
     */
    protected function getModule($name=null) {
        if ( null===$name ) {
            return X::system()->getModuleManager()->get($this->getGroupName());
        } else {
            return X::system()->getModuleManager()->get($name);
        }
    }
    
    /**
     * @param string $name
     * @return \X\Core\Service\XService
     */
    protected function getService( $name ) {
        return X::system()->getServiceManager()->get($name);
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    protected function getCurrentAccount() {
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        return $accountService->getCurrentAccount();
    }
    
    /**
     * @return void
     */
    protected function checkLoginRequirement() {
        if ( null === $this->getCurrentAccount() ) {
            $this->gotoURL('/index.php?module=account&action=login/index');
        }
    }
    
    /**
     * @return string
     */
    public function getAssetsURL () {
        return X::system()->getConfiguration()->get('assets-base-url');
    }
}
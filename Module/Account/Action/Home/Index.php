<?php
namespace X\Module\Account\Action\Home;
/**
 *
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualUserHome;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Index extends VisualUserHome {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $account = $accountService->get($id);
        
        if ( null === $account ) {
            $this->throw404();
        }
        
        $this->gotoURL('/?module=lunome&action=movie/home/index', array('id'=>$id));
    }
}
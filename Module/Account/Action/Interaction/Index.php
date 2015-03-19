<?php
namespace X\Module\Account\Action\Interaction;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * 
 */
class Index extends Userinteraction {
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
        
        $friendInformation = $account->getProfileManager();
        $this->interactionMenuParams = array('id'=>$id);
        
        /* Main */
        $name   = 'USER_INTERACTION_INDEX';
        $path   = $this->getParticleViewPath('Interaction/Index');
        $option = array();
        $data   = array(
            'items'         => $this->interactionMenu, 
            'parameters'    => empty($this->interactionMenuParams) ? '' : '&'.http_build_query($this->interactionMenuParams),
        );
        $this->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '与'.$friendInformation->get('nickname').'互动';
    }
}
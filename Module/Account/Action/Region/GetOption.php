<?php
namespace X\Module\Account\Action\Region;
/**
 * 
 */
use X\Core\X;
use X\Util\Action\Visual;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 * GetOption
 * @author Michael Luthor <michaelluthor@163.com>
 */
class GetOption extends Visual { 
    /**
     * @param string $parent
     * @param string $selected
     */
    public function runAction( $parent='', $selected='' ) {
        /* @var $service AccountService */
        $service = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $regions = $service->getRegionManager()->find(array('parent'=>$parent));
        
        /* Load particle view. */
        $name   = 'REGION_OPTIONS';
        $path   = $this->getParticleViewPath('Region/GetOption');
        $option = array();
        $data   = array( 'regions'=>$regions, 'selected'=>$selected);
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}
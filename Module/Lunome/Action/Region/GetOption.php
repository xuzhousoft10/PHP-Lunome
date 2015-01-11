<?php
/**
 * The action file for connectus action.
 */
namespace X\Module\Lunome\Action\Region;

/**
 * 
 */
use X\Util\Action\Visual;
use X\Module\Lunome\Service\Region\Service as RegionService;

/**
 * The action class for connectus action.
 * @author Unknown
 */
class GetOption extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $parent, $selected='' ) {
        /* @var $servie RegionService */
        $servie = $this->getService(RegionService::getServiceName());
        $regions = $servie->getAll($parent);
        
        /* Load particle view. */
        $name   = 'REGION_OPTIONS';
        $path   = $this->getParticleViewPath('Region/GetOption');
        $option = array();
        $data   = array( 'regions'=>$regions, 'selected'=>$selected);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}
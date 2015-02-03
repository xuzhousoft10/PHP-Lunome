<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Region;

/**
 * use statements
 */
use X\Util\Action\Visual;
use X\Module\Lunome\Service\Region\Service as RegionService;

/**
 * GetOption
 * @author Michael Luthor <michaelluthor@163.com>
 */
class GetOption extends Visual { 
    /**
     * @param string $parent
     * @param string $selected
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
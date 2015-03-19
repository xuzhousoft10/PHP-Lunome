<?php
namespace X\Module\Account\Service\Account\Core\Manager;
use X\Module\Account\Service\Account\Core\Model\AccountRegionModel;
use X\Module\Account\Service\Account\Core\Instance\Region;
/**
 * 
 */
class RegionManager {
    /**
     * @param mixed $criteria
     * @return \X\Module\Account\Service\Account\Core\Instance\Region[]
     */
    public function find( $criteria ) {
        $regions = AccountRegionModel::model()->findAll($criteria);
        foreach ( $regions as $index => $region ) {
            $regions[$index] = new Region($region);
        }
        return $regions;
    }
}
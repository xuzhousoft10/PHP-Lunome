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
    
    /**
     * @param string $id
     * @return \X\Module\Account\Service\Account\Core\Instance\Region
     */
    public function get($id) {
        $region = AccountRegionModel::model()->findByPrimaryKey($id);
        if ( null === $region ) {
            return null;
        }
        return new Region($region);
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function getNameByID( $id ) {
        $region = $this->get($id);
        return (null===$region) ? '' : $region->get('name');
    }
    
    public function searchNonFriends() {
        
    }
}
<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieRegionModel;
use X\Module\Movie\Service\Movie\Core\Instance\Region;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * 
 */
class RegionManager {
    /**
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Region
     */
    public function find( $condition=null ) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        if ( !$condition->hasOrder() ) {
            $condition->addOrder('count', 'DESC');
        }
        
        $regions = MovieRegionModel::model()->findAll($condition);
        foreach ( $regions as $index => $region ) {
            $regions[$index] = new Region($region);
        }
        return $regions;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Region
     */
    public function get( $id ) {
        $region = MovieRegionModel::model()->findByPrimaryKey($id);
        return (null===$region) ? '其他' : new Region($region);
    }
}
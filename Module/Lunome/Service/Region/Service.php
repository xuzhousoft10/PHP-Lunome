<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Region;

/**
 * 
 */
use X\Module\Lunome\Model\Region\RegionModel;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @return \X\Module\Lunome\Model\Region\RegionModel[]
     */
    public function getAll( $parent='' ) {
        $regions = RegionModel::model()->findAll( array('parent'=>$parent) );
        return $regions;
    }
    
    /**
     * @param unknown $id
     * @return Ambigous <\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord, NULL>
     */
    public function get( $id ) {
        return RegionModel::model()->findByPrimaryKey($id);
    }
    
    /**
     * @param unknown $regionID
     * @return string
     */
    public function getNameByID( $regionID ) {
        $region = RegionModel::model()->findByPrimaryKey($regionID);
        return (null === $region) ? '' : $region->name;
    }
    
    public function addRegion( $data ) {
        $region = new RegionModel();
        $region->setAttributeValues($data);
        $region->save();
        return $region;
    }
    
    public function updateRegion( $id, $data ) {
        $region = RegionModel::model()->findByPrimaryKey($id);
        $region->setAttributeValues($data);
        $region->save();
        return $region;
    }
}
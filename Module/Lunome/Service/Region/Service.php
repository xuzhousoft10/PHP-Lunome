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
     * @param unknown $regionID
     * @return string
     */
    public function getNameByID( $regionID ) {
        $region = RegionModel::model()->findByPrimaryKey($regionID);
        return (null === $region) ? '' : $region->name;
    }
}
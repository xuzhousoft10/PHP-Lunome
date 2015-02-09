<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\People;

use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Model\People\PeopleModel;
/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @param number $position
     * @param number $length
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function getAll( $position=0, $length=0 ) {
        $criteria = new Criteria();
        $criteria->position = $position;
        $criteria->limit = $length;
        return PeopleModel::model()->findAll($criteria);
    }
    
    public function count() {
        return PeopleModel::model()->count();
    }
    
    public function get( $poepleId ) {
        return PeopleModel::model()->findByPrimaryKey($poepleId);
    }
    
    public function has( $peopleId ) {
        return PeopleModel::model()->exists(array('id'=>$peopleId));
    }
    
    public function add( $data ) {
        $people = new PeopleModel();
        $people->setAttributeValues($data);
        $people->save();
        return $people;
    }
    
    public function update( $id, $data ) {
        $people = PeopleModel::model()->findByPrimaryKey($id);
        $people->setAttributeValues($data);
        $people->save();
        return $people;
    }
}
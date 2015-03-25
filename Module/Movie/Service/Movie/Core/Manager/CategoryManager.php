<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Category;
use X\Module\Movie\Service\Movie\Core\Model\MovieCategoryModel;
/**
 * 
 */
class CategoryManager {
    /**
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Category
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
        
        $categories = MovieCategoryModel::model()->findAll($condition);
        foreach ( $categories as $index => $category ) {
            $categories[$index] = new Category($category);
        }
        return $categories;
    }
}
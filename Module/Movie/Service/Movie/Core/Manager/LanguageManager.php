<?php
namespace X\Module\Movie\Service\Movie\Core\Manager;
/**
 *
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieLanguageModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Language;
/**
 * 
 */
class LanguageManager {
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
        
        $languages = MovieLanguageModel::model()->findAll($condition);
        foreach ( $languages as $index => $language ) {
            $languages[$index] = new Language($language);
        }
        return $languages;
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Language
     */
    public function get($id) {
        $language = MovieLanguageModel::model()->findByPrimaryKey($id);
        return (null===$language) ? null : new Language($language);
    }
}
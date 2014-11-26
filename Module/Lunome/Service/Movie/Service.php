<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

/**
 * Use statement
 */
use X\Module\Lunome\Util\Service\Media;
use X\Module\Lunome\Model\Movie\MovieRegionModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Model\Movie\MovieLanguageModel;
use X\Module\Lunome\Model\Movie\MovieCategoryModel;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\Movie\MovieModel;
use X\Module\Lunome\Model\Movie\MovieCategoryMapModel;

/**
 * The service class
 */
class Service extends Media {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMediaName()
     */
    public function getMediaName() {
        return '电影';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMarkNames()
     */
    public function getMarkNames() {
        return array(
            self::MARK_UNMARKED     => '未标记',
            self::MARK_INTERESTED   => '想看',
            self::MARK_WATCHED      => '已看',
            self::MARK_IGNORED      => '忽略',
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaModelName()
     */
    protected function getMediaModelName() {
        return 'X\\Module\\Lunome\\Model\\Movie\\MovieModel';
    }
    
    /**
     * @param unknown $condition
     */
    protected function getExtenCondition( $condition ) {
        $con = ConditionBuilder::build();
        if ( isset($condition['date']) ) {
            $con->between('date', $condition['date'].'-01-01', $condition['date'].'-12-31');
        }
        if ( isset($condition['region']) ) {
            $con->equals('region_id', $condition['region']);
        }
        if ( isset($condition['language']) ) {
            $con->equals('language_id', $condition['language']);
        }
        if ( isset($condition['category']) ) {
            $categoryCondition = array();
            $categoryCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $categoryCondition['category_id'] = $condition['category'];
            $categoryCondition = MovieCategoryMapModel::query()->activeColumns(array('movie_id'))->find($categoryCondition);
            $con->exists($categoryCondition);
        }
        return $con;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieRegionModel[]
     */
    public function getRegions() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $regions = MovieRegionModel::model()->findAll($criteria);
        return $regions;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieRegionModel
     */
    public function getRegionById( $regionId ) {
        return MovieRegionModel::model()->findByPrimaryKey($regionId);
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieLanguageModel
     */
    public function getLanguageById( $languageId ) {
        return MovieLanguageModel::model()->findByPrimaryKey($languageId);
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieCategoryModel[]
     */
    public function getCategoriesByMovieId( $movieId ) {
        $categories = MovieCategoryMapModel::model()->findAll(array('movie_id'=>$movieId));
        foreach ( $categories as $index => $category ) {
            $categories[$index] = $category->category_id;
        }
        $categories = MovieCategoryModel::model()->findAll(array('id'=>$categories));
        return $categories;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieLanguageModel[]
     */
    public function getLanguages() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $languages = MovieLanguageModel::model()->findAll($criteria);
        return $languages;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieCategoryModel[]
     */
    public function getCategories() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $languages = MovieCategoryModel::model()->findAll($criteria);
        return $languages;
    }
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
}
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
use X\Service\XDatabase\Core\Exception as DBException;
use X\Module\Lunome\Model\Movie\MovieShortCommentModel;
use X\Module\Lunome\Model\Movie\MovieClassicDialogueModel;

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
        if ( isset($condition['name']) ) {
            $con->includes('name', substr($condition['name'], 1));
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
        if ( 0 === count($categories) ) {
            return array();
        }
        
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
    
    /**
     * @param unknown $id
     * @param unknown $categories
     */
    public function setCategories( $id, $categories ) {
        $oldCategoryMaps = MovieCategoryMapModel::model()->findAll(array('movie_id'=>$id));
        foreach ( $oldCategoryMaps as $oldCategoryMap ) {
            $oldCategory = MovieCategoryModel::model()->findByPrimaryKey($oldCategoryMap->category_id);
            $oldCategory->count --;
            $oldCategory->save();
            
            $oldCategoryMap->delete();
        }
        
        foreach ( $categories as $category ) {
            $map = new MovieCategoryMapModel();
            $map->category_id = $category;
            $map->movie_id = $id;
            $map->save();
            
            $newCategory = MovieCategoryModel::model()->findByPrimaryKey($category);
            $newCategory->count ++;
            $newCategory->save();
        }
    }
    
    /**
     *
     * @param unknown $id
     * @param unknown $media
     * @return \X\Module\Lunome\Model\Movie\MovieModel
     */
    public function update( $movie, $id=null ) {
        $mediaModelName = $this->getMediaModelName();
        $language = array('new'=>$movie['language_id']);
        $region   = array('new'=>$movie['region_id']);
        
        /* @var $mediaModel \X\Util\Model\Basic */
        if ( empty($id) ) {
            $mediaModel = new $mediaModelName();
        } else {
            $mediaModel = $mediaModelName::model()->findByPrimaryKey($id);
            $language['old'] = $mediaModel->language_id;
            $region['old'] = $mediaModel->region_id;
        }
        
        foreach ( $movie as $attr => $value ) {
            $mediaModel->set($attr, $value);
        }
    
        try {
            $mediaModel->save();
            
            if ( !empty($language['new']) ) {
                $languageModel = MovieLanguageModel::model()->findByPrimaryKey($language['new']);
                $languageModel->count ++;
                $languageModel->save();
            }
            
            if ( !empty($region['new']) ) {
                $regionModel = MovieRegionModel::model()->findByPrimaryKey($region['new']);
                $regionModel->count ++;
                $regionModel->save();
            }
            
            if ( isset($language['old']) && !empty($language['old']) ) {
                $languageModel = MovieLanguageModel::model()->findByPrimaryKey($language['old']);
                $languageModel->count --;
                $languageModel->save();
            }
            
            if ( !empty($region['old']) && !empty($region['old']) ) {
                $regionModel = MovieRegionModel::model()->findByPrimaryKey($region['old']);
                $regionModel->count --;
                $regionModel->save();
            }
            
            $this->logAction($this->getActionName('add'), $mediaModel->id);
        } catch ( DBException $e ){}
        return $mediaModel;
    }
    
    /**
     * @param unknown $id
     * @param unknown $content
     * @return \X\Module\Lunome\Model\Movie\MovieShortCommentModel
     */
    public function addShortComment( $id, $content ) {
        $model = new MovieShortCommentModel();
        $model->movie_id = $id;
        $model->content = $content;
        $model->commented_at = date('Y-m-d H:i:s', time());
        $model->commented_by = $this->getCurrentUserId();
        $model->parent_id = '00000000-0000-0000-0000-000000000000';
        $model->save();
        return $model;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function getShortComments( $id, $parent=null, $position, $length ) {
        $condition = array();
        $condition['movie_id'] = $id;
        $condition['parent_id'] = (null===$parent)?'00000000-0000-0000-0000-000000000000':$id;
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = $condition;
        $criteria->addOrder('commented_at', 'DESC');
        
        $comments = MovieShortCommentModel::model()->findAll($criteria);
        return $comments;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function countShortComments( $id, $parent=null ) {
        $condition = array();
        $condition['movie_id'] = $id;
        $condition['parent_id'] = (null===$parent)?'00000000-0000-0000-0000-000000000000':$id;
        
        $count = MovieShortCommentModel::model()->count($condition);
        return $count;
    }
    
    /**
     * @param unknown $id
     * @param unknown $position
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MovieClassicDialogueModel[]
     */
    public function getClassicDialogues( $id, $position, $length ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id);
        $criteria->position = $position;
        $criteria->limit = $length;
        $dialogues = MovieClassicDialogueModel::model()->findAll($criteria);
        return $dialogues;
    }
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
}
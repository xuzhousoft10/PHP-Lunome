<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

/**
 * Use statement
 */
use X\Core\X;
use X\Module\Lunome\Model\Movie\MovieRegionModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Model\Movie\MovieLanguageModel;
use X\Module\Lunome\Model\Movie\MovieCategoryModel;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Util\Expression as SQLExpression;
use X\Module\Lunome\Model\Movie\MovieModel;
use X\Module\Lunome\Model\Movie\MovieCategoryMapModel;
use X\Service\XDatabase\Core\Exception as DBException;
use X\Module\Lunome\Model\Movie\MovieShortCommentModel;
use X\Module\Lunome\Model\Movie\MovieClassicDialogueModel;
use X\Module\Lunome\Model\Movie\MovieUserRateModel;
use X\Module\Lunome\Model\Movie\MoviePosterModel;
use X\Service\QiNiu\Service as QiniuService;
use X\Module\Lunome\Model\Movie\MovieDirectorMapModel;
use X\Module\Lunome\Model\Movie\MovieActorMapModel;
use X\Module\Lunome\Model\Movie\MovieCharacterModel;
use X\Module\Lunome\Model\Movie\MovieUserMarkModel;
use X\Module\Lunome\Model\Account\AccountFriendshipModel;
use X\Module\Lunome\Model\Account\AccountInformationModel;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Module\Lunome\Model\Movie\MovieInvitationModel;
use X\Service\XDatabase\Core\SQL\Condition\Condition as SQLCondition;
use X\Module\Lunome\Service\User\Service as UserService;
use X\Service\XDatabase\Core\SQL\Func\Count;
use X\Service\XDatabase\Service as DatabaseService;
use X\Util\ImageResize;
use X\Module\Lunome\Util\Exception as LunomeException;
use X\Module\Lunome\Model\Movie\MovieUserVisited;
use X\Module\Lunome\Model\Movie\MovieDirectorModel;
use X\Module\Lunome\Model\Movie\MovieActorModel;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var unknown
     */
    protected static $serviceName = 'Movie';
    
    /**
     *
     * @param unknown $condition
     * @param unknown $position
     * @param unknown $limit
     */
    public function findAll( $condition=null, $position=0, $length=0 ) {
        $mediaModelName = $this->getMediaModelName();
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->limit = $length;
        $criteria->position = $position;
        $medias = $mediaModelName::model()->findAll($criteria);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    /**
     *
     * @param string $condition
     */
    public function count($condition=null) {
        $mediaModelName = $this->getMediaModelName();
        return $mediaModelName::model()->count($condition);
    }
    
    /**
     *
     * @param unknown $id
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function get( $id ) {
        $mediaModelName = $this->getMediaModelName();
        /* @var $mediaModel \X\Util\Model\Basic */
        $mediaModel = $mediaModelName::model()->find(array('id'=>$id));
        if ( null === $mediaModel ) {
            throw new LunomeException("Can not find model '{$mediaModelName}' by primary key '{$id}'");
        }
        return $mediaModel->toArray();
    }
    
    /**
     * @param unknown $id
     */
    public function has( $id ) {
        $mediaModelName = $this->getMediaModelName();
        return $mediaModelName::model()->exists(array('id'=>$id));
    }
    
    /**
     *
     * @param unknown $movieId
     * @param unknown $mark
     */
    public function unmark( $id, $mark ) {
        $mediaModelName = $this->getMediaModelName();
    
        $deleteCondition = array();
        $deleteCondition['movie_id'] = $id;
        $deleteCondition['account_id'] = $this->getCurrentUserId();
        MovieUserMarkModel::model()->deleteAll($deleteCondition);
        $this->logAction($this->getActionName('unmark'), $id, self::RESULT_CODE_SUCCESS, null, $mark);
    }
    
    /**
     * @param unknown $type
     * @param unknown $limit
     * @return array
     */
    public function getTopList( $type, $limit ) {
        $modelName = $this->getMediaModelName();
        $modelTableName = $modelName::model()->getTableFullName();
        $markTableName = MovieUserMarkModel::model()->getTableFullName();
    
        $query = SQLBuilder::build()->select()
        ->expression('medias.id', 'id')
        ->expression('medias.name', 'name')
        ->expression(new Count('marks.id'), 'mark_count')
        ->from($modelTableName, 'medias')
        ->from($markTableName, 'marks')
        ->where(array('marks.mark'=>$type, 'medias.id'=>new SQLExpression('`marks`.`movie_id`')))
        ->limit($limit)
        ->groupBy('marks.movie_id')
        ->orderBy('mark_count', 'DESC')
        ->toString();
        $result = $this->getDb()->query($query);
        return $result;
    }
    
    /**
     *
     */
    public function addCover( $id, $coverPath ) {
        $mediaModelName = $this->getMediaModelName();
        $type = $this->getMediaType();
    
        $image = new ImageResize($coverPath);
        $image->resize(200, 300);
        $tempPath = tempnam(sys_get_temp_dir(), 'RSCV');
        $image->save($tempPath);
    
        /* @var $qiniuService QiniuService */
        $qiniuService = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniuService->putFile($tempPath, null, "$id.jpg");
        unlink($tempPath);
    
        $model = $mediaModelName::model()->findByPrimaryKey($id);
        $model->has_cover = 1;
        $model->save();
        return $model;
    }
    
    /**
     * @param unknown $id
     */
    public function deleteCover( $id ) {
        $mediaModelName = $this->getMediaModelName();
        $type = $this->getMediaType();
    
        /* @var $qiniuService QiniuService */
        $qiniuService = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniuService->delete("$id.jpg");
    
        $model = $mediaModelName::model()->findByPrimaryKey($id);
        $model->has_cover = 0;
        $model->save();
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database
     */
    protected function getDb() {
        return X::system()->getServiceManager()->get(DatabaseService::getServiceName())->getDatabaseManager()->get();
    }
    
    /**
     * Get the condition of unmarked.
     *
     * @return \X\Service\XDatabase\Core\SQL\Condition\Builder
     */
    protected function getUnmarkedMediaCondition() {
        $mediaModelName = $this->getMediaModelName();
        $mediaTable = $mediaModelName::model()->getTableFullName();
    
        /* Generate basic condition to ignore marked movies. */
        $markedCondition = array();
        $markedCondition['movie_id'] = new SQLExpression($mediaTable.'.id');
        $markedCondition['account_id'] = $this->getCurrentUserId();
        $markedMedias = MovieUserMarkModel::query()->addExpression('movie_id')->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->notExists($markedMedias);
        return $basicCondition;
    }
    
    /**
     * Get the condition for marked media
     * @param unknown $mark
     */
    protected function getMarkedMediaCondition($mark, $account=0) {
        $mediaModelName = $this->getMediaModelName();
        $mediaTable = $mediaModelName::model()->getTableFullName();
    
        $markedCondition = array();
        $markedCondition['movie_id'] = new SQLExpression($mediaTable.'.id');
    
        if ( 0 === $account ) {
            $markedCondition['account_id'] = $this->getCurrentUserId();
        } else if (null === $account) {
            // all users
        } else {
            $markedCondition['account_id'] = $account;
        }
        $markedCondition['mark'] = $mark;
    
        $markCondition = MovieUserMarkModel::query()->addExpression('movie_id')->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    /**
     *
     * @return string
     */
    protected function getCurrentUserId() {
        $currentUser = $this->getUserService()->getCurrentUser();
        return isset($currentUser['ID']) ? $currentUser['ID'] : null;
    }
    
    /**
     * @return string
    */
    protected function getMediaType() {
        $class = explode('\\', get_class($this));
        array_pop($class);
        return strtolower(array_pop($class));
    }
    
    /**
     * @return \X\Module\Lunome\Service\User\Service
     */
    protected function getUserService() {
        return X::system()->getServiceManager()->get(UserService::getServiceName());
    }
    
    /**
     * @param string $action
     * @param string $mediaId
     * @param string $code
     * @param string $message
     * @param string $comment
     */
    protected function logAction($action, $mediaId, $code=self::RESULT_CODE_SUCCESS, $message=null, $comment=null) {
        $this->getUserService()->getAccount()->logAction($action, $mediaId, $code, $message, $comment);
    }
    
    /**
     * @param unknown $action
     * @return string
     */
    protected function getActionName( $action ) {
        $name = sprintf('%s_%s', $this->getMediaModelName(), $action);
        return strtoupper($name);
    }
    
    /*  */
    const RESULT_CODE_SUCCESS = 0;
    
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
        if ( isset( $condition['director'] ) ) {
            $directors = MovieDirectorModel::model()->findAll(array('name'=>$condition['director']));
            foreach ( $directors as $index => $director ) {
                $directors[$index] = $director->id;
            }
            $directorCondition = array();
            $directorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $directorCondition['director_id'] = $directors;
            $directorCondition = MovieDirectorMapModel::query()->addExpression('movie_id')->find($directorCondition);
            $con->exists($directorCondition);
        }
        if ( isset( $condition['actor'] ) ) {
            $actors = MovieActorModel::model()->findAll(array('name'=>$condition['actor']));
            foreach ( $actors as $index => $actor ) {
                $actors[$index] = $actor->id;
            }
            $actorCondition = array();
            $actorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $actorCondition['actor_id'] = $actors;
            $actorCondition = MovieActorMapModel::query()->addExpression('movie_id')->find($actorCondition);
            $con->exists($actorCondition);
        }
        if ( isset($condition['category']) ) {
            $categoryCondition = array();
            $categoryCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $categoryCondition['category_id'] = $condition['category'];
            $categoryCondition = MovieCategoryMapModel::query()->addExpression('movie_id')->find($categoryCondition);
            $con->exists($categoryCondition);
        }
        if ( isset($condition['name']) ) {
            $con->includes('name', $condition['name']);
        }
        return $con;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieRegionModel[]
     */
    public function getRegions($position=0, $limit=0) {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $criteria->position = $position;
        $criteria->limit = $limit;
        $regions = MovieRegionModel::model()->findAll($criteria);
        return $regions;
    }
    
    public function countRegions() {
        return MovieRegionModel::model()->count();
    }
    
    public function addRegion( $data ) {
        $region = new MovieRegionModel();
        $region->setAttributeValues($data);
        $region->save();
        return $region;
    }
    
    public function updateRegion( $id, $data ) {
        $region = MovieRegionModel::model()->findByPrimaryKey($id);
        $region->setAttributeValues($data);
        $region->save();
        return $region;
    }
    
    public function moveMoviesFromARegionToAnother( $sourceRegionId, $targetRegionId ) {
        $sourceRegion = MovieRegionModel::model()->findByPrimaryKey($sourceRegionId);
        $targetRegion = MovieRegionModel::model()->findByPrimaryKey($targetRegionId);
    
        if ( null===$sourceRegion || null===$targetRegion ) {
            return false;
        }
    
        $condition = array('region_id'=>$sourceRegionId);
        $sourceRegionCount = MovieModel::model()->count($condition);
        MovieModel::model()->updateAll(array('region_id'=>$targetRegionId), $condition);
        $sourceRegion->count = 0;
        $sourceRegion->save();
    
        $targetRegion->count += $sourceRegionCount;
        $targetRegion->save();
        return true;
    }
    
    public function deleteRegion( $regionId ) {
        $region = MovieRegionModel::model()->findByPrimaryKey($regionId);
        if ( null === $region) {
            return false;
        }
    
        $condition = array('region_id'=>$regionId);
        MovieModel::model()->updateAll(array('region_id'=>''), $condition);
        $region->delete();
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
     * @return \X\Module\Lunome\Model\Movie\MovieLanguageModel[]
     */
    public function getLanguages($position=0, $limit=0) {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $criteria->position = $position;
        $criteria->limit = $limit;
        $languages = MovieLanguageModel::model()->findAll($criteria);
        return $languages;
    }
    
    public function countLanguage() {
        return MovieLanguageModel::model()->count();
    }
    
    public function addLanguage( $data ) {
        $language = new MovieLanguageModel();
        $language->setAttributeValues($data);
        $language->save();
        return $language;
    }
    
    public function updateLanguage( $id, $newData ) {
        $language = MovieLanguageModel::model()->findByPrimaryKey($id);
        $language->setAttributeValues($newData);
        $language->save();
        return $language;
    }
    
    public function moveMoviesFromALanguageToAnother( $sourceLanguageId, $targetLanguageId ) {
        $sourceLanguage = MovieLanguageModel::model()->findByPrimaryKey($sourceLanguageId);
        $targetLanguage = MovieLanguageModel::model()->findByPrimaryKey($targetLanguageId);
        
        if ( null===$sourceLanguage || null===$targetLanguage ) {
            return false;
        }
        
        $condition = array('language_id'=>$sourceLanguageId);
        $sourceLanguageCount = MovieModel::model()->count($condition);
        MovieModel::model()->updateAll(array('language_id'=>$targetLanguageId), $condition);
        $sourceLanguage->count = 0;
        $sourceLanguage->save();
        
        $targetLanguage->count += $sourceLanguageCount;
        $targetLanguage->save();
        return true;
    }
    
    public function deleteLanguage( $languageId ) {
        $language = MovieLanguageModel::model()->findByPrimaryKey($languageId);
        if ( null === $language) {
            return false;
        }
        
        $condition = array('language_id'=>$languageId);
        MovieModel::model()->updateAll(array('language_id'=>''), $condition);
        $language->delete();
    }
    
    /**
     * @param unknown $id
     * @return MovieCategoryModel
     */
    public function getCategoryById( $id ) {
        $category = MovieCategoryModel::model()->findByPrimaryKey($id);
        return $category;
    }
    
    public function addCategory( $data ) {
        $category = new MovieCategoryModel();
        $category->setAttributeValues($data);
        $category->save();
        return $category;
    }
    
    public function updateCategory( $id, $newData ) {
        $category = MovieCategoryModel::model()->findByPrimaryKey($id);
        $category->setAttributeValues($newData);
        $category->save();
        return $category;
    }
    
    public function deleteCategory( $categoryId ) {
        MovieCategoryModel::model()->deleteAll(array('id'=>$categoryId));
        MovieCategoryMapModel::model()->deleteAll(array('category_id'=>$categoryId));
        return true;
    }
    
    public function moveMoviesFromACategoryToAnotherCategory( $sourceCategoryId, $targetCategoryId ) {
        if ( $sourceCategoryId === $targetCategoryId ) {
            return false;
        }
        
        $sourceCategory = MovieCategoryModel::model()->findByPrimaryKey($sourceCategoryId);
        if ( null === $sourceCategory ) {
            return false;
        }
        
        $targetCategory = MovieCategoryModel::model()->findByPrimaryKey($targetCategoryId);
        if ( null === $targetCategory ) {
            return false;
        }
        
        $maps = MovieCategoryMapModel::model()->findAll(array('category_id'=>$sourceCategoryId));
        foreach ( $maps as $map ) {
            $sourceCategory->count --;
            
            $condition = array('movie_id'=>$map->movie_id, 'category_id'=>$map->category_id);
            if ( MovieCategoryMapModel::model()->exists($condition) ) {
                $map->delete();
            } else {
                $map->category_id = $targetCategory->id;
                $map->save();
                
                $targetCategory->count ++;
            }
        }
        
        $targetCategory->save();
        $sourceCategory->save();
        
        return true;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieCategoryModel[]
     */
    public function getCategories($position=0, $limit=0) {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $criteria->position = $position;
        $criteria->limit = $limit;
        $languages = MovieCategoryModel::model()->findAll($criteria);
        return $languages;
    }
    
    public function countCategory() {
        return MovieCategoryModel::model()->count();
    }
    
    public function addCategoryToMovie( $movieId, $categoryId ) {
        if ( MovieCategoryMapModel::model()->exists(array('movie_id'=>$movieId, 'category_id'=>$categoryId)) ) {
            return false;
        }
        
        $category = MovieCategoryModel::model()->findByPrimaryKey($categoryId);
        if ( null === $category ) {
            return false;
        }
        $category->count ++;
        $category->save();
        
        $map = new MovieCategoryMapModel();
        $map->movie_id = $movieId;
        $map->category_id = $categoryId;
        $map->save();
        return true;
    }
    
    public function removeCategoryFromMovie( $movieId, $categoryId ) {
        $map = MovieCategoryMapModel::model()->find(array('movie_id'=>$movieId, 'category_id'=>$categoryId));
        if ( null === $map ) {
            return false;
        }
        $map->delete();
        
        $category = MovieCategoryModel::model()->findByPrimaryKey($categoryId);
        if ( null === $category ) {
            return false;
        }
        $category->count --;
        $category->save();
        return true;
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
    
    public function addDirectorToMovie( $movieId, $name ) {
        $name = trim($name);
        if ( empty($name) ) {
            return false;
        }
        
        $people = MovieDirectorModel::model()->find(array('name'=>$name));
        if ( null === $people ) {
            $people = new MovieDirectorModel();
            $people->count = 1;
            $people->name = $name;
            $people->save();
        }
        
        $condition = array('movie_id'=>$movieId, 'director_id'=>$people->id);
        if ( MovieDirectorMapModel::model()->exists($condition) ) {
            return false;
        }
        
        $map = new MovieDirectorMapModel();
        $map->movie_id = $movieId;
        $map->director_id = $people->id;
        $map->save();
    }
    
    public function removeDirectorFromMovie( $movieId, $directorId ) {
        $condition = array('movie_id'=>$movieId, 'director_id'=>$directorId);
        $map = MovieDirectorMapModel::model()->find($condition);
        if ( null === $map ) {
            return false;
        }
        $map->delete();
        return true;
    }
    
    public function addActorToMovie( $movieId, $name ) {
        $name = trim($name);
        if ( empty($name) ) {
            return false;
        }
    
        $people = MovieActorModel::model()->find(array('name'=>$name));
        if ( null === $people ) {
            $people = new MovieDirectorModel();
            $people->name = $name;
            $people->count = 1;
            $people->save();
        }
    
        $condition = array('movie_id'=>$movieId, 'actor_id'=>$people->id);
        if ( MovieActorMapModel::model()->exists($condition) ) {
            return false;
        }
    
        $map = new MovieActorMapModel();
        $map->movie_id = $movieId;
        $map->actor_id = $people->id;
        $map->save();
    }
    
    public function removeActorFromMovie( $movieId, $actorId ) {
        $condition = array('movie_id'=>$movieId, 'actor_id'=>$actorId);
        $map = MovieActorMapModel::model()->find($condition);
        if ( null === $map ) {
            return false;
        }
        $map->delete();
        return true;
    }
    
    /**
     * 获取指定标记的电影的账户列表。
     * @param string $movieId
     * @param integer $mark
     * @param integer $position
     * @param integer $length
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel[]
     */
    public function getMarkedAccounts( $movieId, $mark, $position, $length=10 ) {
        $condition = ConditionBuilder::build();
    
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $markConditon = array();
        $markConditon['movie_id'] = $movieId;
        $markConditon['mark'] = $mark;
        $markConditon['account_id'] = new SQLExpression($releatedAttrName);;
        $markConditon = MovieUserMarkModel::query()->addExpression('id')->findAll($markConditon);
        $condition->exists($markConditon);
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $length;
        
        $accounts = AccountInformationModel::model()->findAll($criteria);
        return $accounts;
    }
    
    /**
     * 获取指定标记的电影的当前用户的好友列表。
     * @param string $movieId
     * @param integer $mark
     * @param integer $position
     * @param integer $length
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel[]
     */
    public function getMarkedFriendAccounts(  $movieId, $mark, $position, $length=10 ) {
        $condition = ConditionBuilder::build();
        
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $markConditon = array();
        $markConditon['movie_id'] = $movieId;
        $markConditon['mark'] = $mark;
        $markConditon['account_id'] = new SQLExpression($releatedAttrName);;
        $markConditon = MovieUserMarkModel::query()->addExpression('id')->findAll($markConditon);
        $condition->exists($markConditon);
        
        $friendCondition = array();
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition['account_me'] = $this->getCurrentUserId();
        $friendCondition = AccountFriendshipModel::query()->addExpression('id')->find($friendCondition);
        $condition->exists($friendCondition);
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $length;
        
        $accounts = AccountInformationModel::model()->findAll($criteria);
        return $accounts;
    }
    
    public function getUnvisitedAndUnmarkedMovie( $condition, $position=0, $limit=0 ) {
        $basicCondition = $this->getUnmarkedMediaCondition();
        $basicCondition->addCondition($this->getExtenCondition($condition));
        
        $mediaTable = MovieModel::model()->getTableFullName();
        /* Generate basic condition to ignore marked movies. */
        $visitedCondition = array();
        $visitedCondition['movie_id'] = new SQLExpression($mediaTable.'.id');
        $visitedCondition['account_id'] = $this->getCurrentUserId();
        $visitedCondition = MovieUserVisited::query()->activeColumns(array('movie_id'))->find($visitedCondition);
        $visitedCondition = ConditionBuilder::build()->notExists($visitedCondition);
        $basicCondition->addCondition($visitedCondition);
        
        $criteria = new Criteria();
        $criteria->condition = $basicCondition;
        $criteria->limit = $limit;
        $criteria->position = $position;
        $criteria->addOrder('date', 'DESC');
        $medias = MovieModel::model()->findAll($criteria);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    public function markMovieAsVisited( $movieId ) {
        $mark = new MovieUserVisited();
        $mark->account_id = $this->getCurrentUserId();
        $mark->movie_id = $movieId;
        $mark->save();
    }
    
    const SCORE_OPERATOR_EQUAL = SQLCondition::OPERATOR_EQUAL;
    const SCORE_OPERATOR_GREATER = SQLCondition::OPERATOR_GREATER_THAN;
    const SCORE_OPERATOR_LESS_OR_EQUAL = SQLCondition::OPERATOR_LESS_OR_EQUAL;
}
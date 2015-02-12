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
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\Movie\MovieModel;
use X\Module\Lunome\Model\Movie\MovieCategoryMapModel;
use X\Service\XDatabase\Core\Exception as DBException;
use X\Module\Lunome\Model\Movie\MovieShortCommentModel;
use X\Module\Lunome\Model\Movie\MovieClassicDialogueModel;
use X\Module\Lunome\Model\Movie\MovieUserRateModel;
use X\Module\Lunome\Model\Movie\MoviePosterModel;
use X\Service\QiNiu\Service as QiniuService;
use X\Module\Lunome\Model\Movie\MovieDirectorMapModel;
use X\Module\Lunome\Model\People\PeopleModel;
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
use X\Module\Lunome\Service\Configuration\Service as ConfigService;
use X\Service\XDatabase\Core\SQL\Expression;
use X\Util\ImageResize;
use X\Module\Lunome\Util\Exception as LunomeException;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
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
     * Get unmarked medias.
     *
     * @param unknown $condition
     * @param number $length
     * @param number $position
     * @return array
     */
    public function getUnmarked($condition=array(), $length=0, $position=0) {
        $mediaModelName = $this->getMediaModelName();
        $basicCondition = $this->getUnmarkedMediaCondition();
        $basicCondition->addCondition($this->getExtenCondition($condition));
        $criteria = new Criteria();
        $criteria->condition = $basicCondition;
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->addOrder('date', 'DESC');
        $medias = $mediaModelName::model()->findAll($criteria);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    /**
     * @param integer $mark
     * @param mixed $condition, 默认为array()
     * @param integer $length, 默认为0
     * @param integer $position, 默认为0
     * @param string $account, 默认为0
     * @return array
     */
    public function getMarked( $mark, $condition=array(), $length=0, $position=0, $account=0) {
        $mediaModelName = $this->getMediaModelName();
        $basicCondition = $this->getMarkedMediaCondition($mark, $account);
        $basicCondition->addCondition($this->getExtenCondition($condition));
        $criteria = new Criteria();
        $criteria->condition = $basicCondition;
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->addOrder('date', 'DESC');
        $medias = $mediaModelName::model()->findAll($criteria);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    /**
     *
     * @return number
     */
    public function countUnmarked( $condition=array() ) {
        $mediaModel = $this->getMediaModelName();
        $basicCondition = $this->getUnmarkedMediaCondition();
        $basicCondition->addCondition($this->getExtenCondition($condition));
        $count = $mediaModel::model()->count($basicCondition);
        return $count;
    }
    
    /**
     * @param unknown $mark
     * @return number
     */
    public function getMarkedCount( $mark ) {
        if ( self::MARK_UNMARKED === $mark ) {
            return $this->countUnmarked();
        } else {
            return $this->countMarked($mark);
        }
    }
    
    /**
     *
     * @param unknown $mark
     * @return number
     */
    public function countMarked( $mark, $id=null, $user=0, $extCondition=array() ) {
        $mediaModel = $this->getMediaModelName();
        $condition = $this->getMarkedMediaCondition($mark, $user);
        if ( null !== $id ) {
            $condition->equals('id', $id);
        }
        $condition->addCondition($this->getExtenCondition($extCondition));
        $count = $mediaModel::model()->count($condition);
        return $count;
    }
    
    /**
     *
     * @param unknown $movieId
     * @param unknown $mark
     */
    public function mark( $id, $mark ) {
        $mediaModelName = $this->getMediaModelName();
    
        $deleteCondition = array();
        $deleteCondition['movie_id'] = $id;
        $deleteCondition['account_id'] = $this->getCurrentUserId();
        MovieUserMarkModel::model()->deleteAll($deleteCondition);
    
        if ( 0 === $mark*1 ) {
            return;
        }
    
        $markModel = new MovieUserMarkModel();
        $markModel->movie_id = $id;
        $markModel->account_id = $this->getCurrentUserId();
        $markModel->mark = $mark;
        $markModel->save();
        $this->logAction($this->getActionName('mark'), $id, self::RESULT_CODE_SUCCESS, null, $mark);
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
     * 获取指定Media的标记, 如果标记找不到， 则会返回0.
     * @param string $id    要查询的Media的ID
     * @param string $user  要查询的用户ID， 如果不填或者为null， 则为当前用户
     * @return integer
     */
    public function getMark( $id, $user=null ) {
        $condition = array();
        $condition['movie_id']      = $id;
        $condition['account_id']    = ( null === $user ) ? $this->getCurrentUserId() : $user;
        $mark = MovieUserMarkModel::model()->find($condition);
        return ( null === $mark ) ? 0 : $mark->mark*1;
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
        ->addExpression('medias.id', 'id')
        ->addExpression('medias.name', 'name')
        ->addExpression(new Count('marks.id'), 'mark_count')
        ->addTable($modelTableName, 'medias')
        ->addTable($markTableName, 'marks')
        ->where(array('marks.mark'=>$type, 'medias.id'=>new Expression('`marks`.`movie_id`')))
        ->limit($limit)
        ->groupBy('marks.movie_id')
        ->orderBy('mark_count', 'DESC')
        ->toString();
        $result = $this->getDb()->query($query);
        return $result;
    }
    
    /**
     * 返回Media默认的封面图片。
     * @return string
     */
    public function getMediaDefaultCoverURL() {
        $type = $this->getMediaType();
        $key = 'default_'.$type.'_cover_image';
        /* @var $configService ConfigService */
        $configService = X::system()->getServiceManager()->get(ConfigService::getServiceName());
        return $configService->get($key);
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
        return X::system()->getServiceManager()->get(DatabaseService::getServiceName())->getDatabase();
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
        $markedMedias = MovieUserMarkModel::query()->activeColumns(array('movie_id'))->find($markedCondition);
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
    
        $markCondition = MovieUserMarkModel::query()->activeColumns(array('movie_id'))->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    /**
     *
     * @return string
     */
    protected function getCurrentUserId() {
        $currentUser = $this->getUserService()->getCurrentUser();
        return $currentUser['ID'];
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
     * 根据ID返回相应Media的封面图片URL
     * @param unknown $id
     * @return string
     */
    public function getCoverURL( $id, $refresh=false ) {
        $isDebug = X::system()->getConfiguration()->get('is_debug');
        if ( $isDebug ) {
            $url = '7vijk1.com1.z0.glb.clouddn.com';
        } else {
            $url = '7sbycx.com1.z0.glb.clouddn.com';
        }
        $path = 'http://'.$url.'/'.$id.'.jpg';
        if ( $refresh ) {
            $path .= '?rand='.uniqid();
        }
        return $path;
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
        if ( isset( $condition['director'] ) ) {
            $directors = PeopleModel::model()->findAll(array('name'=>$condition['director']));
            foreach ( $directors as $index => $director ) {
                $directors[$index] = $director->id;
            }
            $directorCondition = array();
            $directorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $directorCondition['director_id'] = $directors;
            $directorCondition = MovieDirectorMapModel::query()->activeColumns(array('movie_id'))->find($directorCondition);
            $con->exists($directorCondition);
        }
        if ( isset( $condition['actor'] ) ) {
            $actors = PeopleModel::model()->findAll(array('name'=>$condition['actor']));
            foreach ( $actors as $index => $actor ) {
                $actors[$index] = $actor->id;
            }
            $actorCondition = array();
            $actorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $actorCondition['actor_id'] = $actors;
            $actorCondition = MovieActorMapModel::query()->activeColumns(array('movie_id'))->find($actorCondition);
            $con->exists($actorCondition);
        }
        if ( isset($condition['category']) ) {
            $categoryCondition = array();
            $categoryCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $categoryCondition['category_id'] = $condition['category'];
            $categoryCondition = MovieCategoryMapModel::query()->activeColumns(array('movie_id'))->find($categoryCondition);
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
    public function getShortComments( $id, $parent=null, $position=null, $length=null ) {
        $condition = array();
        $condition['movie_id'] = $id;
        $condition['parent_id'] = (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent;
        
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
    public function getShortCommentsFromFriends( $movieID, $parent=null, $position, $length ) {
        $currentUserID = $this->getCurrentUserId();
        
        $condition = ConditionBuilder::build();
        $condition->is('movie_id', $movieID);
        $condition->is('parent_id', (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent);
        
        $friendCondition = array();
        $releatedAttrName = MovieShortCommentModel::model()->getAttributeQueryName('commented_by');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
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
    public function countShortCommentsFromFriends( $movieID, $parent=null ) {
        $currentUserID = $this->getCurrentUserId();
    
        $condition = ConditionBuilder::build();
        $condition->is('movie_id', $movieID);
        $condition->is('parent_id', (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent);
    
        $friendCondition = array();
        $releatedAttrName = MovieShortCommentModel::model()->getAttributeQueryName('commented_by');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $count = MovieShortCommentModel::model()->count($condition);
        return $count;
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
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countClasicDialogues( $movieID ) {
        return MovieClassicDialogueModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function addClassicDialogues( $id, $content ) {
        $dialogues = new MovieClassicDialogueModel();
        $dialogues->movie_id = $id;
        $dialogues->content = $content;
        $dialogues->save();
    }
    
    /**
     * @param unknown $id
     * @param unknown $score
     */
    public function setRateScore( $id, $score ) {
        $currentUserId = $this->getCurrentUserId();
        $condition = array('movie_id'=>$id, 'account_id'=>$currentUserId);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            $rate = new MovieUserRateModel();
        }
        $rate->movie_id = $id;
        $rate->score = $score;
        $rate->account_id = $this->getCurrentUserId();
        $rate->save();
    }
    
    /**
     * @param unknown $id
     */
    public function getRateScore( $id ) {
        $currentUserId = $this->getCurrentUserId();
        $condition = array('movie_id'=>$id, 'account_id'=>$currentUserId);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            return 0;
        }
        return $rate->score*1;
    }
    
    /**
     * @param unknown $id
     * @param unknown $path
     * @param unknown $type
     */
    public function addPoster( $id, $path ) {
        $poster = new MoviePosterModel();
        $poster->movie_id = $id;
        $poster->save();
        
        /* @var $qiniu QiniuService */
        $qiniu = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniu->setBucket('lunome-movie-posters');
        $qiniu->putFile($path, null, $poster->id);
    }
    
    /**
     * @param unknown $id
     * @param unknown $position
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MoviePosterModel[]
     */
    public function getPosters( $id, $position, $length ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id);
        $criteria->position = $position;
        $criteria->limit = $length;
        $dialogues = MoviePosterModel::model()->findAll($criteria);
        return $dialogues;
    }
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countPosters( $movieID ) {
        return MoviePosterModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $id
     * @return string
     */
    public function getPosterUrlById( $id ) {
        return 'http://7sbyuj.com1.z0.glb.clouddn.com/'.$id;
    }
    
    /**
     * @param unknown $id
     * @return \X\Module\Lunome\Model\People\PeopleModel[]
     */
    public function getDirectors( $id ) {
        $directors = MovieDirectorMapModel::model()->findAll(array('movie_id'=>$id));
        if ( empty($directors) ) {
            return array();
        }
        
        foreach ( $directors as $index => $director ) {
            $directors[$index] = $director->director_id;
        }
        $directors = PeopleModel::model()->findAll(array('id'=>$directors));
        return $directors;
    }
    
    public function addDirectorToMovie( $movieId, $name ) {
        $name = trim($name);
        if ( empty($name) ) {
            return false;
        }
        
        $people = PeopleModel::model()->find(array('name'=>$name));
        if ( null === $people ) {
            $people = new PeopleModel();
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
    
    /**
     * @param unknown $id
     */
    public function getActors( $id ) {
        $actors = MovieActorMapModel::model()->findAll(array('movie_id'=>$id));
        if ( empty($actors) ) {
            return array();
        }
        
        foreach ( $actors as $index => $actor ) {
            $actors[$index] = $actor->actor_id;
        }
        $directors = PeopleModel::model()->findAll(array('id'=>$actors));
        return $directors;
    }
    
    public function addActorToMovie( $movieId, $name ) {
        $name = trim($name);
        if ( empty($name) ) {
            return false;
        }
    
        $people = PeopleModel::model()->find(array('name'=>$name));
        if ( null === $people ) {
            $people = new PeopleModel();
            $people->name = $name;
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
     * @param unknown $movieId
     * @param unknown $characterInfo
     * @return \X\Module\Lunome\Model\Movie\MovieCharacterModel
     */
    public function addCharacter( $movieId, $characterInfo, $image=null ) {
        $character = new MovieCharacterModel();
        $character->movie_id = $movieId;
        $character->name = $characterInfo['name'];
        $character->description = $characterInfo['description'];
        $character->save();
        
        if ( null !== $image ) {
            /* @var $qiniu QiniuService */
            $qiniu = X::system()->getServiceManager()->get(QiniuService::getServiceName());
            $qiniu->setBucket('lunome-movie-characters');
            $qiniu->putFile($image, null, $character->id);
        }
        return $character;
    }
    
    /**
     * @param unknown $movieId
     * @param unknown $offset
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MovieCharacterModel[]
     */
    public function getCharacters( $movieId, $offset=0, $length=0 ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$movieId);
        $criteria->position = $offset;
        $criteria->limit = $length;
        $characters = MovieCharacterModel::model()->findAll($criteria);
        return $characters;
    }
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countCharacters( $movieID ) {
        return MovieCharacterModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $characterId
     * @return string
     */
    public function getCharacterUrlById( $characterId ) {
        return 'http://7te9pc.com1.z0.glb.clouddn.com/'.$characterId;
    }
    
    /**
     * 统计全网用户对指定电影做指定标记的数量。
     * @param string $movieID 查询的电影的ID
     * @param integer $mark 查询的标记代码。标记宏为 Service::MARK_*
     * @return integer
     */
    public function countMarkedUsers( $movieID, $mark ) {
        $condition = array();
        $condition['mark']      = $mark;
        $condition['movie_id']  = $movieID;
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    /**
     * 统计当前用户的好友对指定电影标记的数量。
     * @param string $movieID 查询的电影的ID
     * @param string $mark 查询的标记代码。标记宏为 Service::MARK_*
     * @return integer
     */
    public function countMarkedFriends( $movieID, $mark ) {
        $currentUserID = $this->getCurrentUserId();
        
        $condition = ConditionBuilder::build();
        $condition->is('mark', $mark);
        $condition->is('movie_id', $movieID);
        
        $friendCondition = array();
        $releatedAttrName = MovieUserMarkModel::model()->getAttributeQueryName('account_id');
        $friendCondition['account_me'] = $currentUserID;
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
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
        $markConditon = MovieUserMarkModel::query()->activeColumns(array('id'))->findAll($markConditon);
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
        $markConditon = MovieUserMarkModel::query()->activeColumns(array('id'))->findAll($markConditon);
        $condition->exists($markConditon);
        
        $friendCondition = array();
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition['account_me'] = $this->getCurrentUserId();
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $length;
        
        $accounts = AccountInformationModel::model()->findAll($criteria);
        return $accounts;
    }
    
    /**
     * @param unknown $accounts
     */
    public function getInterestedMovieSetByAccounts ( $accounts, $length=0, $position=0 ) {
        $marks = SQLBuilder::build()->select()
            ->addExpression('id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 'mark_accounts')
            ->where(ConditionBuilder::build()
                ->is('mark', self::MARK_INTERESTED)
                ->in('account_id', $accounts)
                ->addCondition(new SQLExpression('mark_movies.id=mark_accounts.id'))
            );
        
        $markTable = MovieUserMarkModel::model()->getTableFullName();
        $sql = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->from(array('mark_movies'=>$markTable))
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()->exists($marks))
            ->having('COUNT(`movie_id`)='.count($accounts));
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = ConditionBuilder::build()->in('id', $sql);
        $movies = MovieModel::model()->findAll($criteria);
        return $movies;
    }
    
    /**
     * @param unknown $accountID
     * @param unknown $movieID
     * @param unknown $comment
     */
    public function sendMovieInvitation( $accountID, $movieID, $comment, $view ) {
        $invitation = new MovieInvitationModel();
        $invitation->account_id = $accountID;
        $invitation->invited_at = date('Y-m-d H:i:s');
        $invitation->inviter_id = $this->getCurrentUserId();
        $invitation->movie_id = $movieID;
        $invitation->comment = $comment;
        $invitation->save();
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Movie\\MovieInvitationModel';
        $sourceId       = $invitation->id;
        $recipiendId    = $accountID;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $recipiendId);
        return $invitation;
    }
    
    /**
     * @param unknown $invitationID
     * @param unknown $answer
     * @param unknown $comment
     * @param unknown $view
     */
    public function answerMovieInvitation( $invitationID, $answer, $comment, $view ) {
        /* @var $invitation \X\Module\Lunome\Model\Movie\MovieInvitationModel  */
        $invitation = MovieInvitationModel::model()->findByPrimaryKey($invitationID);
        $invitation->answer = (self::INVITATION_ANSWER_YES===$answer*1) ? self::INVITATION_ANSWER_YES : self::INVITATION_ANSWER_NO;
        $invitation->answered_at = date('Y-m-d H:i:s');
        $invitation->answer_comment = $comment;
        $invitation->save();
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Movie\\MovieInvitationModel';
        $sourceId       = $invitation->id;
        $recipiendId    = $invitation->inviter_id;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $recipiendId);
        return $invitation;
    }
    
    /**
     * @param unknown $accounts
     * @param unknown $score
     * @param string $operator
     * @return Ambigous <\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord, NULL>
     */
    public function getWatchedMoviesByAccounts( $accounts, $score, $operator='=', $length=0, $position=0 ) {
        $markAccountsCondition = SQLBuilder::build()->select()
            ->addExpression('id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 't2')
            ->where(ConditionBuilder::build()
                ->is('t1.id', new SQLExpression('`t2`.`id`'))
                ->is('mark', self::MARK_WATCHED)
                ->in('t2.account_id', $accounts)
            );
        
        $markCondition = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 't1')
            ->groupBy('t1.movie_id')
            ->having('COUNT(t1.account_id) = '.count($accounts))
            ->where(ConditionBuilder::build()->exists($markAccountsCondition));
        
        $movieCondition = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->addTable(MovieUserRateModel::model()->getTableFullName())
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()
                ->in('account_id', $accounts)
                ->addSigleCondition('score', $operator, $score)
                ->in('movie_id', $markCondition)
            );
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = ConditionBuilder::build()->in('id', $movieCondition);
        $movies = MovieModel::model()->findAll($criteria);
        return $movies;
    }
    
    public function getUnvisitedAndUnmarkedMovie( $condition, $position=0, $limit=0 ) {
        return $this->getUnmarked($condition, $limit, $position);
    }
    
    const SCORE_OPERATOR_EQUAL = SQLCondition::OPERATOR_EQUAL;
    const SCORE_OPERATOR_GREATER = SQLCondition::OPERATOR_GREATER_THAN;
    const SCORE_OPERATOR_LESS_OR_EQUAL = SQLCondition::OPERATOR_LESS_OR_EQUAL;
    
    const INVITATION_ANSWER_YES = 1;
    const INVITATION_ANSWER_NO = 2;
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
}
<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Service;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Model\Movie\MovieUserMarkModel;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Lunome\Service\User\Service as UserService;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Service\XDatabase\Core\SQL\Func\Count;
use X\Service\XDatabase\Service as DatabaseService;
use X\Module\Lunome\Service\Configuration\Service as ConfigService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Core\SQL\Expression;
use X\Service\QiNiu\Service as QiniuService;
use X\Util\ImageResize;
use X\Module\Lunome\Util\Exception as LunomeException;

/**
 * 
 */
abstract class Media extends \X\Core\Service\XService {
    /**
     * 
     */
    abstract public function getMediaName();
    
    /**
     * 
     */
    abstract public function getMarkNames();
    
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
    
    const MARK_UNMARKED = 0;
    
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
     * 根据ID返回相应Media的封面图片URL
     * @param unknown $id
     * @return string
     */
    public function getCoverURL( $id ) {
        $type = $this->getMediaType();
        $path = 'http://lunome.qiniudn.com/covers/'.$type.'s/'.$id.'.png';
        return $path;
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
        $qiniuService->putFile($tempPath, "covers/{$type}s", "$id.png");
        unlink($tempPath);
        
        $model = $mediaModelName::model()->findByPrimaryKey($id);
        $model->has_cover = 1;
        $model->save();
    }
    
    /**
     * @param unknown $id
     */
    public function deleteCover( $id ) {
        $mediaModelName = $this->getMediaModelName();
        $type = $this->getMediaType();
        
        /* @var $qiniuService QiniuService */
        $qiniuService = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniuService->delete("covers/{$type}s/$id.png");
        
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
     * Get the name of media model.
     */
    abstract protected function getMediaModelName();
    
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
}

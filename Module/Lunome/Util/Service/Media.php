<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Service;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Exception;
use X\Module\Lunome\Model\MediaPostersModel;
use X\Module\Lunome\Model\MediaUserMarksModel;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Lunome\Service\User\Service as UserService;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Service\XDatabase\Core\SQL\Func\Count;
use X\Service\XDatabase\Service as DatabaseService;

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
        $medias = $mediaModelName::model()->findAll($condition, $length, $position);
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
     * @param unknown $media
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function add( $media ) {
        $mediaModelName = $this->getMediaModelName();
        /* @var $mediaModel \X\Util\Model\Basic */
        $mediaModel = new $mediaModelName();
        foreach ( $media as $attr => $value ) {
            $mediaModel->set($attr, $value);
        }
        $mediaModel->save();
        $this->logAction($this->getActionName('add'), $mediaModel->id);
        return $mediaModel->toArray();
    }
    
    /**
     * 
     * @param unknown $id
     * @param unknown $media
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function update( $id, $media ) {
        $mediaModelName = $this->getMediaModelName();
        /* @var $mediaModel \X\Util\Model\Basic */
        $mediaModel = $mediaModelName::model()->findByPrimaryKey($id);
        foreach ( $media as $attr => $value ) {
            $mediaModel->set($attr, $value);
        }
        $mediaModel->save();
        $this->logAction($this->getActionName('update'), $mediaModel->id);
        return $mediaModel->toArray();
    }
    
    /**
     * 
     * @param unknown $media
     * @param unknown $poster
     */
    public function addPoster( $media, $data ) {
        $poster = new MediaPostersModel();
        $poster->media_type = $this->getMediaModelName();
        $poster->media_id = $media;
        $poster->data = base64_encode($data);
        $poster->save();
        $this->logAction($this->getActionName('poster_add'), $media, self::RESULT_CODE_SUCCESS, null, $poster->id);
        return $poster->toArray();
    }
    
    /**
     * 
     * @param unknown $media
     * @throws Exception
     * @return string
     */
    public function hasPoster( $media ) {
        $mediaModelName = $this->getMediaModelName();
        $condition = array();
        $condition['media_type'] = $mediaModelName;
        $condition['media_id'] = $media;
        return MediaPostersModel::model()->exists($condition);
    }
    
    /**
     * 
     * @param unknown $media
     * @return boolean
     */
    public function deletePoster( $media ) {
        $mediaModelName = $this->getMediaModelName();
        $condition = array();
        $condition['media_type'] = $mediaModelName;
        $condition['media_id'] = $media;
        $poster = MediaPostersModel::model()->findByAttribute($condition);
        $poster->delete();
        $this->logAction($this->getActionName('poster_delete'), $media, self::RESULT_CODE_SUCCESS, null, $poster->id);
    }
    
    /**
     * 
     * @param unknown $id
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function get( $id ) {
        $mediaModelName = $this->getMediaModelName();
        /* @var $mediaModel \X\Util\Model\Basic */
        $mediaModel = $mediaModelName::model()->findByAttribute(array('id'=>$id));
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
        $medias = $mediaModelName::model()->findAll($basicCondition, $length, $position);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    /**
     * 
     * @param unknown $mark
     * @param number $length
     * @param number $position
     * @return unknown
     */
    public function getMarked( $mark, $length=0, $position=0 ) {
        $mediaModelName = $this->getMediaModelName();
        $basicCondition = $this->getMarkedMediaCondition($mark);
        $medias = $mediaModelName::model()->findAll($basicCondition, $length, $position);
        foreach ( $medias as $index => $media ) {
            $medias[$index] = $media->toArray();
        }
        return $medias;
    }
    
    /**
     * 
     * @return number
     */
    public function countUnmarked() {
        $mediaModel = $this->getMediaModelName();
        $basicCondition = $this->getUnmarkedMediaCondition();
        $count = $mediaModel::model()->count($basicCondition);
        return $count;
    }
    
    /**
     * 
     * @param unknown $mark
     * @return number
     */
    public function countMarked( $mark, $id=null, $user=0 ) {
        $mediaModelName = $this->getMediaModelName();
        
        $condition = array();
        $condition['mark'] = $mark;
        $condition['media_type'] = $mediaModelName;
        if ( null !== $id ) {
            $condition['media_id'] = $id;
        }
        if ( 0 === $user ) {
            $condition['account_id'] = $this->getCurrentUserId();
        } else if (null === $user) {
            // all users
        } else {
            $condition['account_id'] = $user;
        }
        $count = MediaUserMarksModel::model()->count($condition);
        return $count;
    }
    
    /**
     * 
     * @param unknown $movieId
     * @throws Exception
     * @return string
     */
    public function getPoster( $id ) {
        $mediaModelName = $this->getMediaModelName();
        $condition = array();
        $condition['media_type'] = $mediaModelName;
        $condition['media_id'] = $id;
        $poster = MediaPostersModel::model()->findByAttribute($condition);
        if ( null === $poster ) {
            throw new Exception("Can not find poster $id");
        }
    
        $content = base64_decode($poster->data);
        if ( false === $content ) {
            throw new Exception("Bad poster : $id");
        }
    
        return $content;
    }
    
    /**
     * 
     * @param unknown $movieId
     * @param unknown $mark
     */
    public function mark( $id, $mark ) {
        $mediaModelName = $this->getMediaModelName();
        
        $deleteCondition = array();
        $deleteCondition['media_type'] = $mediaModelName;
        $deleteCondition['media_id'] = $id;
        $deleteCondition['account_id'] = $this->getCurrentUserId();
        MediaUserMarksModel::model()->deleteAllByAttributes($deleteCondition);
        
        if ( 0 === $mark*1 ) {
            return;
        }
        
        $markModel = new MediaUserMarksModel();
        $markModel->media_id = $id;
        $markModel->media_type = $mediaModelName;
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
        $deleteCondition['media_type'] = $mediaModelName;
        $deleteCondition['media_id'] = $id;
        $deleteCondition['account_id'] = $this->getCurrentUserId();
        MediaUserMarksModel::model()->deleteAllByAttributes($deleteCondition);
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
        $condition['media_type']    = $this->getMediaModelName();
        $condition['media_id']      = $id;
        $condition['account_id']    = ( null === $user ) ? $this->getCurrentUserId() : $user;
        $mark = MediaUserMarksModel::model()->findByAttribute($condition);
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
        $markTableName = MediaUserMarksModel::model()->getTableFullName();
        
        $query = SQLBuilder::build()->select()
            ->addExpression('medias.id', 'id')
            ->addExpression('medias.name', 'name')
            ->addExpression(new Count('marks.id'), 'mark_count')
            ->addTable($modelTableName, 'medias')
            ->addTable($markTableName, 'marks')
            ->where(array('marks.mark'=>$type))
            ->groupBy('marks.media_id')
            ->orderBy('mark_count', 'DESC')
            ->toString();
        $result = $this->getDb()->query($query);
        return $result;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\Database
     */
    protected function getDb() {
        return X::system()->getServiceManager()->get(DatabaseService::getServiceName())->getDb();
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
        $markedCondition['media_id'] = new SQLExpression($mediaTable.'.id');
        $markedCondition['media_type'] = $mediaModelName;
        $markedCondition['account_id'] = $this->getCurrentUserId();
        $markedMedias = MediaUserMarksModel::query()->activeColumns(array('media_id'))->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->notExists($markedMedias);
        return $basicCondition;
    }
    
    /**
     * Get the condition for marked media
     * @param unknown $mark
     */
    protected function getMarkedMediaCondition($mark) {
        $mediaModelName = $this->getMediaModelName();
        $mediaTable = $mediaModelName::model()->getTableFullName();
        
        $markedCondition = array();
        $markedCondition['media_type'] = $mediaModelName;
        $markedCondition['media_id'] = new SQLExpression($mediaTable.'.id');
        $markedCondition['account_id'] = $this->getCurrentUserId();
        $markedCondition['mark'] = $mark;

        $markCondition = MediaUserMarksModel::query()->activeColumns(array('media_id'))->find($markedCondition);
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

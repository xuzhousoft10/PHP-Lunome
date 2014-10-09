<?php
/**
 * 
 */
namespace X\Module\Lunome\Util\Service;

use X\Module\Lunome\Util\Exception;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;

/**
 * 
 */
abstract class Media extends \X\Core\Service\XService {
    /**
     * Get unmarked medias.
     * 
     * @param unknown $condition
     * @param number $length
     * @param number $position
     * @return array
     */
    public function getUnmarked($condition=array(), $length=0, $position=0) {
        $mediaModel = $this->getMediaModelName();
        $basicCondition = $this->getUnmarkedMediaCondition();
        $medias = $mediaModel::model()->findAll($basicCondition, $length, $position);
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
        $mediaModel = $this->getMediaModelName();
        $basicCondition = $this->getMarkedMediaCondition($mark);
        $medias = $mediaModel::model()->findAll($basicCondition, $length, $position);
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
    public function countMarked( $mark ) {
        $markModelName = $this->getMediaMarkModelName();
        $condition = array('account_id'=>$this->getCurrentUserId(),'type'=>$mark);
        $count = $markModelName::model()->count($condition);
        return $count;
    }
    
    /**
     * 
     * @param unknown $movieId
     * @throws Exception
     * @return string
     */
    public function getPoster( $id ) {
        $posterModelName = $this->getMediaPosterModelName();
        $key = $posterModelName::model()->getMediaKey();
        $poster = $posterModelName::model()->findByAttribute(array($key=>$id));
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
        $markModelName = $this->getMediaMarkModelName();
        $key = $markModelName::model()->getMediaKey();
        $condition = array('account_id'=>$this->getCurrentUserId(), $key=>$id);
        $markModelName::model()->deleteAllByAttributes($condition);
    
        $mark = $this->convertMarkNameToMarkValue($mark);
        $markModel = new $markModelName();
        $markModel->account_id = $this->getCurrentUserId();
        $markModel->$key = $id;
        $markModel->type = $mark;
        $markModel->save();
    }
    
    /**
     * 
     * @param unknown $movieId
     * @param unknown $mark
     */
    public function unmark( $id, $mark ) {
        $markModelName = $this->getMediaMarkModelName();
        $key = $markModelName::model()->getMediaKey();
        $mark = $this->convertMarkNameToMarkValue($mark);
        $condition = array('account_id'=>$this->getCurrentUserId(), $key=>$id, 'type'=>$mark);
        $markModelName::model()->deleteAllByAttributes($condition);
    }
    
    /**
     * Get the condition of unmarked.
     *
     * @return \X\Service\XDatabase\Core\SQL\Condition\Builder
     */
    protected function getUnmarkedMediaCondition() {
        $markModelName = $this->getMediaMarkModelName();
        $key = $markModelName::model()->getMediaKey();
        
        $mediaModelName = $this->getMediaModelName();
        $mediaTable = $mediaModelName::model()->getTableFullName();
        
        /* Generate basic condition to ignore marked movies. */
        $markedCondition = array($key=>new SQLExpression($mediaTable.'.id'), 'account_id'=>$this->getCurrentUserId());
        $markedMedias = $markModelName::query()->activeColumns(array($key))->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->notExists($markedMedias);
        return $basicCondition;
    }
    
    /**
     * Get the condition for marked media
     * @param unknown $mark
     */
    protected function getMarkedMediaCondition($mark) {
        $markModelName = $this->getMediaMarkModelName();
        $key = $markModelName::model()->getMediaKey();
        
        $mediaModelName = $this->getMediaModelName();
        $mediaTable = $mediaModelName::model()->getTableFullName();
        
        $mark = $this->convertMarkNameToMarkValue($mark);
        $markedCondition = array(
            $key   => new SQLExpression($mediaTable.'.id'),
            'account_id' => $this->getCurrentUserId(),
            'type'       => $mark
        );
        $markCondition = $markModelName::query()->activeColumns(array($key))->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    /**
     * 
     * @return string
     */
    protected function getCurrentUserId() {
        return 'DEMO-ACCOUNT-ID';
    }
    
    /**
     * Get the name of media model.
     */
    abstract protected function getMediaModelName();
    
    /**
     * Get the name of marked media
     */
    abstract protected function getMediaMarkModelName();
    
    /**
     * Get the name of media poster.
     */
    abstract protected function getMediaPosterModelName();
    
    /**
     * 
     * @param unknown $name
     */
    abstract protected function convertMarkNameToMarkValue($name);
}

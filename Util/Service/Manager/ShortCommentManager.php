<?php
namespace X\Util\Service\Manager;
/**
 * 
 */
use X\Util\Service\Instance\ShortComment;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 *
 */
class ShortCommentManager {
    private $host = null;
    private $commentModelName = null;
    public function __construct( $host, $commentModelName ) {
        $this->host = $host;
        $this->commentModelName = $commentModelName;
    }
    
    /**
     * @param string $content
     * @return \X\Util\Service\Instance\ShortComment
     */
    public function add( $content ) {
        /* @var $commentModel \X\Util\Model\ShortComment */
        $commentModel = new $this->commentModelName();
        $commentModel->content = $content;
        $commentModel->host_id = $this->host->get('id');
        $commentModel->validate();
        $commentModel->save();
        return new ShortComment($commentModel, $this->host);
    }
    
    /**
     * @return \X\Util\Service\Instance\ShortComment[]
     */
    public function find() {
        $criteria = new Criteria();
        $criteria->condition = array('host_id'=>$this->host->get('id'));
        $criteria->addOrder('record_created_at', 'DESC');
        
        $commentModel = $this->commentModelName;
        $comments = $commentModel::model()->findAll($criteria);
        foreach ( $comments as $index => $comment ) {
            $comments[$index] = new ShortComment($comment, $this->host);
        }
        return $comments;
    }
    
    /**
     * @return integer
     */
    public function count() {
        $condition = array('host_id'=>$this->host->get('id'));
        $commentModel = $this->commentModelName;
        return $commentModel::model()->count($condition);
    }
}
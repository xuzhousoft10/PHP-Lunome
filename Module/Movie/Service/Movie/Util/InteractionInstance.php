<?php
namespace X\Module\Movie\Service\Movie\Util;
/**
 *
 */
use X\Util\Service\Manager\VoteManager;
use X\Util\Service\Manager\FavouriteManager;
use X\Util\Service\Manager\ShortCommentManager;
/**
 * 
 */
class InteractionInstance {
    /**
     * @var ShortCommentManager
     */
    private $commentManager = null;
    
    /**
     * @return \X\Util\Service\Manager\ShortCommentManager
     */
    public function getCommentManager() {
        $commentModel = $this->getCommentModel();
        if (false === $commentModel ) {
            return null;
        }
        
        if ( null === $this->commentManager ) {
            $this->commentManager = new ShortCommentManager($this, $commentModel);
        }
        return $this->commentManager;
    }
    
    /**
     * @return boolean
     */
    protected function getCommentModel() {
        return false;
    }
    
    /**
     * @var VoteManager
     */
    private $voteManger = null;
    
    /**
     * @return \X\Util\Service\Manager\VoteManager
     */
    public function getVoteManager() {
        $voteModel = $this->getVoteModel();
        if ( false === $voteModel ) {
            return null;
        }
        
        if ( null === $this->voteManger ) {
            $this->voteManger = new VoteManager($this, $voteModel);
        }
        return $this->voteManger;
    }
    
    /**
     * @return boolean
     */
    protected function getVoteModel() {
        return false;
    }
    
    /**
     * @var FavouriteManager
     */
    private $favouriteManager = null;
    
    /**
     * @return \X\Util\Service\Manager\FavouriteManager
     */
    public function getFavouriteManager() {
        $favouriteModel = $this->getFavouriteModel();
        if ( false === $favouriteModel ) {
            return null;
        }
        
        if ( null === $this->favouriteManager ) {
            $this->favouriteManager = new FavouriteManager($this, $favouriteModel);
        }
        return $this->favouriteManager;
    }
    
    /**
     * @return boolean
     */
    protected function getFavouriteModel() {
        return false;
    }
}
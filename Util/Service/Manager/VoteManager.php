<?php
namespace X\Util\Service\Manager;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 *
 */
class VoteManager {
    /**
     * @var unknown
     */
    private $host = null;
    
    /**
     * @var unknown
     */
    private $voteModelName = null;
    
    /**
     * @param unknown $host
     * @param unknown $voteModelName
     */
    public function __construct( $host, $voteModelName ) {
        $this->host = $host;
        $this->voteModelName = $voteModelName;
    }
    
    public function voteUp() {
        $this->addVote(self::VOTE_UP);
    }
    
    public function voteDown() {
        $this->addVote(self::VOTE_DOWN);
    }
    
    private function addVote( $vote ) {
        $this->clearVoteOfCurrentAccountForThisHost();
        
        /* @var $voteModel \X\Util\Model\Vote */
        $voteModel = $this->voteModelName;
        $voteModel = new $voteModel();
        $voteModel->host_id = $this->host->get('id');
        $voteModel->vote = $vote;
        $voteModel->save();
    }
    
    private function clearVoteOfCurrentAccountForThisHost() {
        $condition = array(
            'host_id'=>$this->host->get('id'), 
            'record_created_by'=>$this->getCurrentAccountID());
        
        $voteModel = $this->voteModelName;
        $voteModel::model()->deleteAll($condition);
    }
    
    /**
     * @return number
     */
    public function countVoteUp() {
        return $this->countVote(self::VOTE_UP);
    }
    
    /**
     * @return number
     */
    public function countVoteDown() {
        return $this->countVote(self::VOTE_DOWN);
    }
    
    /**
     * @return number
     */
    private function countVote( $vote ) {
        $condition = array(
            'host_id' => $this->host->get('id'),
            'vote' => $vote
        );
        $voteModel = $this->voteModelName;
        return $voteModel::model()->count($condition);
    }
    
    /**
     * @return string
     */
    private function getCurrentAccountID() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        return $accountService->getCurrentAccount()->getID();
    }
    
    const VOTE_UP = 1;
    const VOTE_DOWN = -1;
}
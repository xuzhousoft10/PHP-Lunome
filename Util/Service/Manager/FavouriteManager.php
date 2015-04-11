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
class FavouriteManager {
    /**
     * @var unknown
     */
    private $host = null;
    
    /**
     * @var unknown
     */
    private $favouriteModelName = null;
    
    /**
     * @param unknown $host
     * @param unknown $voteModelName
     */
    public function __construct( $host, $favouriteModelName ) {
        $this->host = $host;
        $this->favouriteModelName = $favouriteModelName;
    }
    
    /**
     * @return void
     */
    public function mark() {
        if ( $this->isMyFavourite() ) {
            return;
        }
        
        $favouriteModel = new $this->favouriteModelName();
        $favouriteModel->host_id = $this->host->get('id');
        $favouriteModel->save();
    }
    
    /**
     * @return void
     */
    public function unmark() {
        $favouriteModel = $this->favouriteModelName;
        $condition = array('host_id'=>$this->host->get('id'), 'record_created_by'=>$this->getCurrentAccountID());
        $favouriteModel::model()->deleteAll($condition);
    }
    
    /**
     * @return boolean
     */
    public function isMyFavourite() {
        $favouriteModel = $this->favouriteModelName;
        $condition = array('host_id'=>$this->host->get('id'), 'record_created_by'=>$this->getCurrentAccountID());
        return $favouriteModel::model()->exists($condition);
    }
    
    /**
     * @return int
     */
    public function count() {
        $favouriteModel = $this->favouriteModelName;
        $condition = array('host_id'=>$this->host->get('id'));
        return $favouriteModel::model()->count($condition);
    }
    
    /**
     * @return string
     */
    private function getCurrentAccountID() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        return $accountService->getCurrentAccount()->getID();
    }
}
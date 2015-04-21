<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Model\MovieCriticismModel;
use X\Module\Movie\Service\Movie\Util\InteractionInstance;
use X\Module\Movie\Service\Movie\Core\Model\MovieCriticismFavouriteModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCriticismVoteModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCriticismCommentModel;
/**
 * 
 */
class Criticism extends InteractionInstance {
    /**
     * @var MovieCriticismModel
     */
    private $criticisModel = null;
    
    /**
     * @param MovieCriticismModel $criticisModel
     */
    public function __construct( $criticisModel ) {
        $this->criticisModel = $criticisModel;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->criticisModel->get($name);
    }
    
    /**
     * @return string
     */
    public function getTime() {
        return $this->criticisModel->record_created_at;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getCommenter() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $commenter = $accountService->get($this->criticisModel->record_created_by);
        return $commenter;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getFavouriteModel()
     */
    public function getFavouriteModel() {
        return MovieCriticismFavouriteModel::getClassName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getVoteModel()
     */
    public function getVoteModel() {
        return MovieCriticismVoteModel::getClassName();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Movie\Service\Movie\Util\InteractionInstance::getCommentModel()
     */
    public function getCommentModel() {
        return MovieCriticismCommentModel::getClassName();
    }
}
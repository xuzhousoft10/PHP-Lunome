<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Model\MovieShortCommentModel;
/**
 * 
 */
class ShortComment {
    /**
     * @var MovieShortCommentModel
     */
    private $model = null;
    
    /**
     * @param MovieShortCommentModel $model
     */
    public function __construct( $model ) {
        $this->model = $model;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->model->get($name);
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ShortComment
     */
    public function set($name, $value) {
        $this->model->set($name, $value);
        return $this;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ShortComment
     */
    public function save() {
        $this->model->save();
        return $this;
    }
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getCommenter() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $commenter = $accountService->get($this->model->commented_by);
        return $commenter;
    }
}
<?php
namespace X\Util\Service\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
/**
 *
 */
class ShortComment extends Model {
    /**
     * @var unknown
     */
    private $host = null;
    
    /**
     * @param unknown $model
     * @param string $host
     */
    public function __construct($model, $host=null) {
        $this->model = $model;
        $this->host = $host;
    }
    
    /**
     * @return string
     */
    public function getTime() {
        return $this->model->record_created_at;
    }
    
    /**
     * @var \X\Module\Account\Service\Account\Core\Instance\Account
     */
    private $autor = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function getAuthor() {
        if ( null === $this->autor ) {
            /* @var $accountService AccountService */
            $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
            $this->autor = $accountService->get($this->model->record_created_by);
        }
        return $this->autor;
    }
    
    /**
     * @return string
     */
    public function getContent() {
        return $this->model->content;
    }
    
    /**
     * @return mixed
     */
    public function getHost() {
        return $this->host;
    }
}
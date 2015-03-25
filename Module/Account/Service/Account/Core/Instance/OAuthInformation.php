<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountOauth20Model;
/**
 * 
 */
class OAuthInformation {
    /**
     * @var AccountOauth20Model
     */
    private $OAuthModel = null;
    
    /**
     * @param AccountOauth20Model $OAuthModel
     */
    public function __construct( $OAuthModel ) {
        $this->OAuthModel = $OAuthModel;
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function get( $name ) {
        return $this->OAuthModel->get($name);
    }
}
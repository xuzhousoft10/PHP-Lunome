<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountFriendshipModel;
use X\Module\Account\Service\Account\Core\Manager\ChatManager;
/**
 * 
 */
class Friend {
    /**
     * @var AccountFriendshipModel
     */
    private $friendshipModel = null;
    
    /**
     * @param AccountFriendshipModel $friendshipModel
     */
    public function __construct( $friendshipModel ) {
        $this->friendshipModel = $friendshipModel;
    }
    
    /**
     * @var ChatManager
     */
    private $chatManager = null;
    
    /**
     * @return \X\Module\Account\Service\Account\Core\Manager\ChatManager
     */
    public function getChatManager(){
        if ( null === $this->chatManager ) {
            $this->chatManager = new ChatManager($this->friendshipModel);
        }
        return $this->chatManager;
    }
    
    public function remove(){}
    public function move(){}
}
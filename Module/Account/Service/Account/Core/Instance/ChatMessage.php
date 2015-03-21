<?php
namespace X\Module\Account\Service\Account\Core\Instance;
/**
 * 
 */
use X\Module\Account\Service\Account\Core\Model\AccountChatContentModel;
/**
 * 
 */
class ChatMessage {
    /**
     * @var AccountChatContentModel
     */
    private $chatMessageModel = null;
    
    /**
     * @param AccountChatContentModel $chatMessageModel
     */
    public function __construct( $chatMessageModel ) {
        $this->chatMessageModel = $chatMessageModel;
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function get( $name ) {
        return $this->chatMessageModel->get($name);
    }
    
    /**
     * @return string
     */
    public function getContent() {
        return $this->chatMessageModel->content;
    }
}
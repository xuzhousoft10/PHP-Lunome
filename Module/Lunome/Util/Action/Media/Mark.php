<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * Visual action class
 */
abstract class Mark extends Basic {
    protected $mediaId;
    protected $mark;
    
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id, $mark, $redirect=false ) {
        $isGuest = $this->getService(UserService::getServiceName())->getIsGuest();
        if ( $isGuest ) {
            $this->gotoURL('/index.php?module=lunome&action=user/login/index');
            X::system()->stop();
        }
        
        $this->mediaId = $id;
        $this->mark = $mark;
        $this->getMediaService()->mark($id, $mark);
        if ( $redirect ) {
            $this->goBack();
        }
    }
}
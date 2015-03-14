<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\User\Notification;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Index extends Visual { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( ) {
        $notifications = $this->getUserService()->getUnclosedNotifications();
        foreach ( $notifications as $index => $notification ) {
            $notifications[$index]['view'] = $this->getParticleViewPath($notification['view']);
        }
        
        $name   = 'NOTIFICATION_INDEX';
        $path   = $this->getParticleViewPath('User/Notification/Index');
        $option = array();
        $data   = array('notifications'=>$notifications);
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}
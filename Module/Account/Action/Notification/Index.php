<?php
namespace X\Module\aCCOUNT\Action\Notification;
/**
 * 
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
        $notifications = $this->getCurrentAccount()->getNotificationManager()->find();
        $name   = 'NOTIFICATION_INDEX';
        $path   = $this->getParticleViewPath('Notification/Index');
        $option = array();
        $data   = array('notifications'=>$notifications);
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}
<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\User\Chat;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Region\Service as RegionService;

/**
 * 
 */
class Index extends Visual {
    /**
     * 
     */
    public function runAction( $friend ) {
        $userService = $this->getUserService();
        
        if ( !$userService->getAccount()->hasFriend($friend) ) {
            $this->throw404();
        }
        
        /* @var $regionService RegionService */
        $regionService = X::system()->getServiceManager()->get(RegionService::getServiceName());
        $friendInformation = $userService->getAccount()->getInformation($friend);
        $friendInformation->living_country    = $regionService->getNameByID($friendInformation->living_country);
        $friendInformation->living_province   = $regionService->getNameByID($friendInformation->living_province);
        $friendInformation->living_city       = $regionService->getNameByID($friendInformation->living_city);
        
        $name   = 'USER_CHAT'; 
        $path   = $this->getParticleViewPath('User/Chat/Index');
        $option = array();
        $data   = array('friend'=>$friendInformation);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        $this->getView()->title = '与'.$friendInformation->nickname.'聊天中';
        $userService->getAccount()->markChattingWithFriend($friend);
    }
}
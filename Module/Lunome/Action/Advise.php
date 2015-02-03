<?php
/**
 * The action file for advise action.
 */
namespace X\Module\Lunome\Action;

/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Service\XMail\Service as MailService;

/**
 * The action class for advise action.
 * @author Unknown
 */
class Advise extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $content, $email, $send ) {
        $this->getView()->title = "意见建议 | Lunome";
        
        $this->getView()->loadLayout($this->getLayoutViewPath('Blank'));
        if ( 'send' !== $send ) {
            $name   = 'ADVISE_EDIT';
            $path   = $this->getParticleViewPath('Advise/Edit');
            $option = array();
            $data   = array();
        } else {
            if ( 0 !== strlen(trim($content)) ) {
                $recipients = $this->getModule()->getConfiguration()->get('web_master_email');
                /* @var $mailService MailService */
                $mailService = X::system()->getServiceManager()->get(MailService::getServiceName());
                $subject = sprintf('%s : 来自用户的建议或意见', date('Y-m-d H:i:s', time()));
                $mailService->send($subject, $content, $recipients);
            }
            
            $name   = 'ADVISE_SENDED';
            $path   = $this->getParticleViewPath('Advise/Sended');
            $option = array();
            $data   = array();
        }
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
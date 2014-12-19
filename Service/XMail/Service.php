<?php
/**
 * This file implements the service Movie
 */
namespace X\Service\XMail;

/**
 * 
 */
use X\Service\XMail\Core\Mailer;
use X\Service\XMail\Core\Exception;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    public function afterStart() {
        $path = $this->getPath('Core/PHPMailer/PHPMailerAutoload.php');
        require_once $path;
    }
    
    /**
     * @param unknown $subject
     * @param unknown $content
     * @param unknown $recipients
     * @return \X\Service\XMail\Core\Mailer
     */
    public function create( $subject, $content, $recipients ) {
        $mailer = new Mailer();
        
        $mailer->setupByConfig($this->getConfiguration()->getAll());
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $recipients = is_array($recipients) ? $recipients : explode(';', $recipients);
        foreach ( $recipients as $recipient ) {
            $mailer->addAddress($recipient);
        }
        
        return $mailer;
    }
    
    /**
     * @param unknown $subject
     * @param unknown $content
     * @param array|string $recipients
     */
    public function send( $subject, $content, $recipients ) {
        $mailer = $this->create($subject, $content, $recipients);
        $isSuccessed = $mailer->send();
        if ( !$isSuccessed ) {
            throw new Exception($mailer->ErrorInfo);
        }
    }
}
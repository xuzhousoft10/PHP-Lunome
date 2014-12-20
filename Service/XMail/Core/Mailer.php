<?php
namespace X\Service\XMail\Core;

/**
 * 
 */
class Mailer extends \PHPMailer {
    /**
     * @param unknown $config
     */
    public function setupByConfig( $config ) {
        $handler = 'setupByConfigHandler'.ucfirst($config['handler']);
        $this->$handler($config);
    }
    
    /**
     * @param unknown $config
     */
    private function setupByConfigHandlerSmtp( $config ) {
        $this->isSMTP();
        $this->Host = $config['host'];
        $this->Port = $config['port'];
        $this->From = $config['from'];
        $this->FromName = $config['from name'];
        $this->SMTPAuth = $config['auth_required'];
        if ( $this->SMTPAuth ) {
            $this->Username=$config['username'];
            $this->Password = $config['password'];
        }
    }
}
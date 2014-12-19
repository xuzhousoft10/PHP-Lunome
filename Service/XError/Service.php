<?php
/**
 * Namespace defination.
 */
namespace X\Service\XError;

/**
 * 
 */
use X\Core\X;
use X\Service\XMail\Service as MailService;

/**
 * XError service.
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        if ( 'Default' === $this->getConfiguration()->get('handler') ) {
            return;
        }
        set_error_handler(array($this, 'errorHandler'), E_ALL);
    }
    
    /**
     * The error handler to handle system errors.
     * 
     * @param integer $number The error code
     * @param string $message The message on error happens
     * @param string $file The file path of error includes
     * @param integer $line The line number of error happend.
     * @param array $context The context on error happend.
     */
    public function errorHandler( $number, $message, $file, $line, $context ) {
        ob_start();
        while (@ob_end_clean());
        
        $errorInfo = array(
            'number'    => $number,
            'message'   => $message,
            'file'      => $file,
            'line'      => $line,
            'context'   => $context,
        );
        
        if ( $this->getConfiguration()->get('EmailError') ) {
            $this->emailError($errorInfo);
        }
        
        $reporter = $this->getReporter();
        $reporter->display($errorInfo);
        
        die();
    }
    
    /**
     * Send error message to recipients.
     *  
     * @param string $error The error information
     */
    protected function emailError( $error ) {
        /* @var $mailService MailService */
        $mailService = X::system()->getServiceManager()->get(MailService::getServiceName());
        $subject = $this->getConfiguration()->get('EmailErrorSubject');
        $content = $this->getPath('Core/View/Email.php');
        ob_start();
        ob_implicit_flush(false);
        require $content;
        $content = ob_get_clean();
        $recipients = $this->getConfiguration()->get('EmailErrorRecipients');
        $mailService->send($subject, $content, $recipients);
    }
    
    /**
     * Get the error report for displaying the error.
     * 
     * @return \X\Service\XError\Reporter\ReporterBasic
     */
    protected function getReporter() {
        $handler = $this->getConfiguration()->get('Reporter');
        $handler = '\\X\\Service\\XError\\Core\\Reporter\\'.$handler;
        
        $handler = new $handler();
        return $handler;
    }
}
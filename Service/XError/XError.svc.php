<?php
/**
 * Namespace defination.
 */
namespace X\Service\XError;

/**
 * XError service.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XErrorService extends \X\Core\Service\XService {
    /**
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        if ( 'Default' !== $this->configuration['ErrorReport']['handler'] ) {
            set_error_handler(array($this, 'errorHandler'), E_ALL);
        }
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
        
        if ( 'on' == $this->config['EmailError']['status'] ) {
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
        $mail = new \X\Library\XEmail\XMailer();
        $mail->isSMTP();
        $mail->Host = $this->config['EmailError']['server'];
        $mail->Port = $this->config['EmailError']['port'];
        $mail->From = $this->config['EmailError']['from'];
        $mail->FromName = $this->config['EmailError']['name'];
        $mail->SMTPAuth = true;
        $mail->Username=$this->config['EmailError']['username'];
        $mail->Password = $this->config['EmailError']['password'];
        $mail->Subject = 'Stumoc System Error';
        $path = sprintf('%s/core/views/email.php', dirname(__FILE__));
        ob_start();
        ob_implicit_flush(false);
        require $path;
        $mail->Body = ob_get_clean();
        $recipients = explode(';', $this->config['EmailError']['recipients']);
        foreach ( $recipients as $recipient ) {
            $mail->addAddress($recipient);
        }
        $mail->send();
    }
    
    /**
     * Get the error report for displaying the error.
     * 
     * @return \X\Service\XError\Reporter\ReporterBasic
     */
    protected function getReporter() {
        $handler = $this->config['ErrorReport']['handler'];
        $handlerClass = sprintf('\\X\\Service\\XError\\Reporter\\%sReporter', $handler);
        $handlerFile = sprintf('%s/core/reporter/%s.php', dirname(__FILE__), $handler);
        
        if ( !class_exists($handlerClass, false) ) {
            require $handlerFile;
        }
        
        $handler = new $handlerClass($this->config['ErrorReport']);
        return $handler;
    }
}

return __NAMESPACE__;
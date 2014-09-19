<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Reporter;

/**
 * Default error reporter.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class DefaultReporter extends ReporterAbstract {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XError\Reporter\ReporterAbstract::init()
     */
    protected function init() {}
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XError\Reporter\ReporterAbstract::display()
     */
    public function display( $error ) {
        header('HTTP/1.1 500 Internal Server Error');
        $view = sprintf('%s/../views/error.php', dirname(__FILE__));
        require $view;
    }
}
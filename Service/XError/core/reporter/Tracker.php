<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Reporter;

/**
 * Requirements
 */
require_once 'Service/XError/core/reporter/includes/XTraceItem.php';

/**
 * The trace reporter
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class TrackerReporter extends ReporterAbstract {
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
        extract($error, EXTR_OVERWRITE);
        $view = sprintf('%s/../views/trace.php', dirname(__FILE__));
        require $view;
    }
}
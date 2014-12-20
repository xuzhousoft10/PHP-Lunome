<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Core\Reporter\Util;

/**
 * The interface for error reporter.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
interface InterfaceReporter {
    /**
     * Display the content of error page.
     * 
     * @param array $error The information that pass to error handler.
     * @return void
     */
    public function display( $error );
}
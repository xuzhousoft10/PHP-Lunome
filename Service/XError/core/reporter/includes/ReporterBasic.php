<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Reporter;

/**
 * The abstract class for reporter.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class ReporterBasic implements InterfaceReporter {
    /**
     * This value holds the config values for this reporter.
     * 
     * @var array
     */
    protected $config = null;
    
    /**
     * 
     * @param array $config
     */
    public function __construct( $config ) {
        $this->config = $config;
        $this->init();
    }
    
    /**
     * Init this report handler.
     * 
     * @return void
     */
    abstract protected function init();
    
    /**
     * Display the content of error page.
     * 
     * @param array $error The information that pass to error handler.
     * @return void
     */
    abstract public function display( $error );
}
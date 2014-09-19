<?php
/**
 * Namespace defination
 */
namespace X\Service\XRequest\Core;

/**
 * The interface for request recorder.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
interface InterfaceRequestRecorder {
    /**
     * Prepare the request recorder.
     * 
     * @return void
     */
    public function setup();
    
    /**
     * Record the request by given data.
     * 
     * @param array $request The request information.
     * @return void
     */
    public function record($request);
    
    /**
     * Get request history by given criteria.
     * 
     * @param array $criteria The condition to search history.
     * @return array
     */
    public function getHistory($criteria);
}
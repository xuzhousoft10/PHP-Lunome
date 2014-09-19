<?php
/**
 * Namespce defination
 */
namespace X\Service\XView\Core;

/**
 * The basic view class.
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   Version 0.0.0
 * @version 0.0.0
 */
abstract class View extends Basic implements InterfaceView {
    /**
     * Display the content of view to output.
     * 
     * @return void
     */
    abstract public function display();
}
<?php
/**
 * The session handler interface.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
if (version_compare(phpversion(), '5.4.0', '<')) {
    require dirname(__FILE__).DIRECTORY_SEPARATOR.'InterfaceHandlerCustom.php';
} else {
    require dirname(__FILE__).DIRECTORY_SEPARATOR.'InterfaceHandlerOriginal.php';
}
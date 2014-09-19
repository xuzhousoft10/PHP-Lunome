<?php
/**
 * 
 */
namespace X\Library\XUtil;

/**
 * The directory util handler
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Directory {
    /**
     * Create a new directory.
     * 
     * @param string $path The target directory to create
     * @param string $model The model to the directory
     * @return boolean
     */
    public static function Create( $path, $model=0777, $ds='/' ) {
        $parent = dirname($path);
        if ( !is_dir($parent) ) {
            self::Create($parent, $model);
        }
        
        if ( !is_dir($path) ) {
            mkdir($path, $model);
        }
        
        return true;
    }
}
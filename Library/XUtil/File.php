<?php
/**
 *
 */
namespace X\Library\XUtil;

/**
 * The util handler for file
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class File {
    /**
     * Get the mime type of the file.
     * 
     * @param string $file The path of file to get mime from.
     * @return string
     */
    public static function getMimeType( $file ) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $file);
        finfo_close($finfo);
        return $mimetype;
    }
    
    /**
     * Send the file to the browser.
     * 
     * @param string $path The path of file to sended.
     * @return boolean
     */
    public static function sendToBrowser( $path, $name=null ) {
        if ( !file_exists($path) ) {
            return false;
        }
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.(is_null($name) ? basename($path):$name));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        ob_clean();
        flush();
        readfile($path);
        exit;
        return true;
    }
}


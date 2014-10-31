<?php
/**
 * 
 */
namespace X\Core\Util;

/**
 * 
 */
use X\Core\Basic;

/**
 * 
 */
class XUtil extends Basic {
    /**
     * 将php数组保存到指定文件中。
     * 
     * @param string $path 目标文件路径。
     * @param array $data 数组内容。
     */
    public static function storeArrayToPHPFile( $path, $data ) {
        $var = var_export($data, true);
        $content = array();
        $content[] = '<?php';
        $content[] = "return $var;";
        $content = implode("\n", $content);
        file_put_contents($path, $content);
    }
    
    /**
     * 根据路径删除指定的文件， 文件可以是普通文件或者目录。
     * @param string $path 要删除文件的路径。
     */
    public static function deleteFile( $path ) {
        if ( !is_dir($path) ) {
            unlink($path);
        } else {
            $files = scandir($path);
            foreach ( $files as $file ) {
                if ( '.' === $file || '..' === $file ) { continue; }
                self::deleteFile($path.DIRECTORY_SEPARATOR.$file);
            }
            rmdir($path);
        }
    }
}
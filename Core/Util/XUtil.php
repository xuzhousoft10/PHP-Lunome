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
}
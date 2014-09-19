<?php
namespace X\Core\Util;
use X\Core\Basic;
class XUtil extends Basic {
    public static function storeArrayToPHPFile( $path, $data ) {
        $var = var_export($data, true);
        $time = date('Y-m-d H:i:s', time());
        $content = array();
        $content[] = '<?php';
        $content[] = "return $var;";
        $content = implode("\n", $content);
        file_put_contents($path, $content);
    }
}
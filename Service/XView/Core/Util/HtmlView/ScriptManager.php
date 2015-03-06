<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class ScriptManager {
    /**
     * This value contains all the script stuff.
     * @var array
     */
    protected $scripts = array(
    /* 'identifier' => array(
     *      'type'=>'text/javascript',
     *      'src'=>'',
     *      'content'=>'',
     *      'defer'=>false,
     *      'charset'=>'UTF-8',
     *      'asyns'=>false,
     * ), */
    );
    
    /**
     * Add script to current page.
     * @param string $identifier The name of script
     * @param string $script The content of script
     * @param string $type The type of script
     */
    public function addString( $name, $script, $type='text/javascript' ) {
        if ( empty($script) ) {
            return;
        }
        
        $this->scripts[$name] = array(
                'type'      => $type,
                'src'       => null,
                'content'   => $script,
                'defer'     => false,
                'charset'   => null,
                'asyns'     => false,
        );
    }
    
    /**
     * Add script file to current page.
     * @param string $identifier The name of script
     * @param string $link The link of script
     * @param string $type The type of script
     * @param string $charset The charset of script 
     * @param string $asyns The asyns of script
     */
    public function addFile( $name, $link, $type='text/javascript', $charset='UTF-8', $asyns=false ) {
        if ( empty($link) ) {
            return;
        }
        
        $this->scripts[$name] = array(
            'type'      => $type,
            'src'       => $link,
            'content'   => null,
            'defer'     => false,
            'charset'   => $charset,
            'asyns'     => $asyns,
        );
    }
    
    /**
     * Get all the scripts of current page.
     * @return array
     */
    public function getList() {
        return array_keys($this->scripts);
    }
    
    /**
     * Check that whether the script exists in this view.
     * @param string $identifier The script identifier
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->scripts[$name]);
    }
    
    /**
     * Get script information from this view.
     * @param string $identifier The script identifier
     * @return array
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Can not find script "'.$name.'".');
        }
        return $this->scripts[$name];
    }
    
    /**
     * Remove Script from current page.
     * @param string $identifier The name of script
     */
    public function remove($name) {
        if ( isset($this->scripts[$name]) ) {
            unset($this->scripts[$name]);
        }
    }
    
    /**
     * Get the content of scripts
     * @return string
     */
    public function toString() {
        $scriptList = array();
        foreach ( $this->scripts as $script ) {
            if ( !empty($script['src']) ) {
                $scriptList[] = '<script type="'.$script['type'].'" src="'.$script['src'].'" charset="'.$script['charset'].'"></script>';
            } else if ( !empty($script['content']) ) {
                $scriptList[] = "<script type=\"{$script['type']}\">\n{$script['content']}\n</script>";
            }
        }
        
        if ( 0 === count($scriptList) ) {
            return null;
        }
        
        $scriptList = implode("\n", $scriptList);
        return $scriptList;
    }
}
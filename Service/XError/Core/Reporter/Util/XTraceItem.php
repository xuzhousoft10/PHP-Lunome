<?php
/**
 * Namespace defination 
 */
namespace X\Service\XError\Core\Reporter\Util;

/**
 * The XtraceItem class
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XTraceItem extends \stdClass {
    /**
     * Get trace items from error.
     * 
     * @return \X\Service\XError\XTraceItem[]
     */
    public static function getTraceItems() {
        $traceItems = array();
        $trace = debug_backtrace();
        $trace = array_reverse($trace);
        array_pop($trace);
        array_pop($trace);
        array_pop($trace);
        array_pop($trace);
        array_pop($trace);
        foreach ( $trace as $traceItem ) {
            $traceItems[] = new XTraceItem($traceItem);
        }
        return $traceItems;
    }
    
    /**
     * The system trace information.
     * 
     * @var array
     */
    protected $traceInfo = null;
    
    /**
     * Initiate the instance.
     * 
     * @param array $traceInfo
     */
    protected function __construct( $traceInfo ) {
        $this->traceInfo = $traceInfo;
    }
    
    /**
     * Get the caller name before error happend.
     * 
     * @return string
     */
    public function getCalledName() {
        $call = sprintf('%s%s%s',
            isset($this->traceInfo['class']) ? $this->traceInfo['class'] : '',
            isset($this->traceInfo['type']) ? $this->traceInfo['type'] : '',
            $this->traceInfo['function']
        );
        return $call;
    }
    
    /**
     * Get parameters to the function before the error happend.
     * 
     * @return array
     */
    public function getParameters() {
        if ( !isset($this->traceInfo['args']) ) {
            return array();
        }
        
        $parameters = array();
        foreach ( $this->traceInfo['args'] as $parameter ) {
            $parameterInfo = array();
            if ( is_null($parameter) ) {
                $parameterInfo['text'] = 'NULL';
            } else if ( is_object($parameter) ) {
                $parameterInfo['text'] = 'Object';
            } else if ( is_array($parameter) ) {
                $parameterInfo['text'] = 'Array';
            } else if ( is_string($parameter) && 10<strlen($parameter) ) {
                $string = substr($parameter, 0, 7).'...';
                $parameterInfo['text'] = sprintf('"%s"', $string);
            } else if ( is_string($parameter) && 10>=strlen($parameter) ) {
                $parameterInfo['text'] = sprintf('"%s"', $parameter);
            } else {
                $parameterInfo['text'] = $parameter;
            }
            
            ob_start();
            ob_implicit_flush(false);
            var_dump($parameter);
            $parameterInfo['detail'] = ob_get_clean();
            $parameters[] = $parameterInfo;
        }
        
        return $parameters;
    }
    
    /**
     * Get the number of parameters.
     * 
     * @return number
     */
    public function getParameterCount() {
        if ( !isset($this->traceInfo['args']) ) {
            return 0;
        }else {
            return count($this->traceInfo['args']);
        }
    }
    
    /**
     * Get context of an error.
     * 
     * @return string
     */
    public function getContext() {
        if ( !isset($this->traceInfo['file']) ) {
            return '';
        }
        
        $contextLine = 10;
        $fileContent = file_get_contents($this->traceInfo['file']);
        $fileContent = htmlentities($fileContent);
        $fileContent = str_replace(' ', '&nbsp;', $fileContent);
        $fileContent = explode(PHP_EOL, $fileContent);
        $line = $this->traceInfo['line']-1;
        $fileContent[$line] = sprintf('<span class="text-danger">%s</span>', $fileContent[$line]);
        if ( $line < $contextLine ) {
            $line = 0;
        } else {
            $line -= $contextLine;
        }
        
        $contextLine *= 2;
        if ( $contextLine > count($fileContent) ) {
            $contextLine = count($fileContent);
        }
        
        $fileContent = array_slice($fileContent, $line, $contextLine);
        
        foreach ( $fileContent as &$lineContent ) {
            $lineContent = sprintf('<span class="text-primary">%s</span>&nbsp;&nbsp;&nbsp;&nbsp;%s', $line+1, $lineContent);
            $line ++;
        }
        
        return implode('<br/>', $fileContent);
    }
    
    /**
     * Get the file name that error happend.
     * 
     * @return string|multitype:
     */
    public function getFileName() {
        if ( !isset($this->traceInfo['file']) ) {
            return '';
        }
        
        return $this->traceInfo['file'];
    }
}
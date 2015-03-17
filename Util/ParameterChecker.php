<?php
namespace X\Util;
/**
 * 
 */
class ParameterChecker {
    /**
     * @param array $parameters
     * @return \X\Util\ParameterChecker
     */
    public static function check($parameters, $method) {
        return new ParameterChecker($parameters, $method);
    }
    
    /**
     * @var array
     */
    private $parameters = array();
    
    /**
     * @var string
     */
    private $methodName = null;
    
    /**
     * @param array $parameters
     */
    protected function __construct($parameters, $methodName) {
        $method = new \ReflectionMethod($methodName);
        $methodParams = $method->getParameters();
        foreach ( $parameters as $index => $parameter ) {
            if ( isset($methodParams[$index]) ) {
                $this->parameters[$methodParams[$index]->getName()] = $parameter;
            } else {
                $this->parameters[] = $parameter;
            }
        }
        $this->methodName = $methodName;
    }
    
    /**
     * @param string $name
     * @return \X\Util\ParameterChecker
     */
    public function notEmpty($name) {
        if ( empty($this->getValue($name)) ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' can not be empty.');
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @return \X\Util\ParameterChecker
     */
    public function isArray( $name ){
        if ( !is_array($this->getValue($name)) ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' must be an array.');
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    private function getValue( $name ) {
        preg_match('/(^[a-zA-Z0-9_]+)(.*$)/', $name, $matched);
        
        $key = $matched[1];
        $exten = $matched[2];
        $accessCode = 'return $this->parameters["'.$key.'"]'.$exten.';';
        $value = eval($accessCode);
        return $value;
    }
}
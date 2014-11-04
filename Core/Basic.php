<?php
/**
 * 
 */
namespace X\Core;

/**
 * 
 */
abstract class Basic extends \stdClass {
    /**
     * 检测属性是否设置，并且不是 NULL。
     * @param string $name 被检查的属性名称
     * @return boolean
     */
    public function __isset($name){
        $getter = sprintf('get%s', ucfirst($name));
        $isset = method_exists($this, $getter) ? (null !== $this->$getter()) : false;
        $isset = $isset || ( method_exists($this, 'get') && null !== $this->get($name) );
        return $isset;
    }
    
    /**
     * 当外部读取一个不存在的属性时， 该方法将会被调用。
     * @param string $name 要获取的属性名称
     * @return mixed
     */
    public function __get( $name ) {
        $getter = sprintf('get%s', ucfirst($name));
        if ( method_exists($this, $getter) ) {
            return call_user_func(array($this, $getter));
        } else if ( method_exists($this, 'get') ) {
            return $this->get($name);
        }
        
        throw new Exception(sprintf('Property "%s" can not be read.', $name));
    }
    
    /**
     * 当外部对一个不存在的属性赋值时， 该方法将会被调用。
     * @param string $name 需要赋值的属性的名称
     * @param mixed $value 对该属性所赋的值
     * @return void
     */
    public function __set( $name, $value ) {
        $setter = sprintf('set%s', ucfirst($name));
        if ( method_exists($this, $setter) ) {
            return call_user_func_array(array($this, $setter), array($value));
        } else if ( method_exists($this, 'get') ) {
            return $this->set($name, $value);
        }
        throw new Exception(sprintf('Property "%s" can not be written.', $name));
    }
    
    /**
     * 当需要将类实例转换为字符串时， 该方法将会被调用。
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
    
    /**
     * 获取该类的字符串表现形式。默认情况下返回类名， 一般需要重写该方法。
     * @return string
     */
    public function toString() {
        return get_class($this);
    }
}
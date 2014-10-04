<?php
/**
 * This file implemented the form model class.
 */
namespace X\Library\XUtil\XFormModel;

/**
 * Use statements
 */
use X\Core\Exception;

/**
 * The html form model.
 * 
 * @author  Mihcael Luthor <michaelluthor@163.com>
 */
abstract class FormModel extends \X\Core\Basic {
    /**
     * construct the object
     */
    public function __construct() {
        $this->describe();
        $this->fillDataFromRequest();
        $this->init();
    }
    
    /**
     * Get value from the model.
     * @param string $attribute The name of attribute.
     * @return mixed
     */
    public function __get($attribute) {
        return $this->getValue($attribute);
    }
    
    /**
     * Set the value to attribute.
     * @param string $attribute
     * @param mixed $value
     */
    public function __set($attribute, $value ) {
        return $this->setValue($attribute, $value);
    }
    
    /**
     * Check if the attribute exists in this model.
     * @param string $attribute The name of attribute to check.
     * @return boolean
     */
    public function __isset($attribute) {
        return $this->has($attribute);
    }
    
    /**
     * The attributes of the model.
     * @var array
     */
    protected $attributes = array(
        /* 'attr'=>array(
         *      'value'         => 'value',
            *      'validators'    => array(),
            *      'default'       => null,
            *      'errors'        => array(),
            *      'type'          => null,
            * )
    * */
    );
    
    /**
     * Describe the model
     */
    abstract protected function describe();
    
    /**
     * Fill the data from request.
     */
    protected function fillDataFromRequest() {
        $index = array_pop(explode('\\', get_class($this)));
        foreach ( $this->attributes as $name => $attribute ) {
            $value = isset($_REQUEST[$index][$name]) ? $_REQUEST[$index][$name] : null;
            if ( null === $value ) {
                $value = $this->attributes[$name]['default'];
            }
            $value = $this->formatValueByType($this->attributes[$name]['type'], $value);
            foreach ( $this->attributes[$name]['validators'] as $validator  ) {
                call_user_func_array($validator, array($this, $name));
            }
            $this->attributes[$name]['value'] = $value;
        }
    }
    
    /**
     * Format the value to given type.
     * @param integer $type
     * @param mixed $value
     * @return number|string|array
     */
    protected function formatValueByType( $type, $value ) {
        switch ( $type ) {
        case self::T_INT    : return intval($value);
        case self::T_STRING : return strval($value);
        case self::T_ARRAY  : return is_array($value) ? $value : array();
        case self::T_NUMERIC: return $value*1;
        }
    }
    
    /**
     * Init this model
     */
    protected function init() {}
    
    /**
     * Add attribute to current model.
     * @param string $name
     * @param mixed $default
     * @return \X\Library\XUtil\XFormModel\FormModel
     */
    protected function addAttribute( $name, $default=null) {
        $this->attributes[$name] = array(
            'value'         => null,
            'validators'    => array(),
            'default'       => $default,
            'errors'        => array(),
        );
        return $this;
    }
    
    /**
     * Add validator to attribute of current model.
     * @param unknown $name
     * @param unknown $validator
     * @return \X\Library\XUtil\XFormModel\FormModel
     */
    protected function addValidator( $name, $validator ) {
        $this->attributes[$name]['validators'][] = $validator;
        return $this;
    }
    
    /**
     * @param unknown $attribute
     * @return mixed
     */
    protected function getValue( $attribute ) {
        $getter = sprintf('get%s', ucfirst($attribute));
        if ( method_exists($this, $getter) ) {
            return call_user_func(array($this, $getter));
        } else {
            return $this->attributes[$attribute]['value'];
        }
    }
    
    /**
     * 
     * @param unknown $attribute
     * @param unknown $value
     * @throws Exception
     */
    protected function setValue( $attribute, $value ) {
        $setter = sprintf('set%s', ucfirst($attribute));
        if ( method_exists($this, $setter) ) {
            call_user_func_array(array($this, $setter), array($value));
        } else if ( isset($this->attributes[$name]) ) {
            $this->attributes[$attribute]['value'] = $value;
        } else {
            throw new Exception(sprintf('"%s" does not exists.', $attribute));
        }
    }
    
    /**
     * 
     * @param unknown $attribute
     * @return boolean
     */
    public function has( $attribute ) {
        $getter = sprintf('get%s', ucfirst($attribute));
        $setter = sprintf('set%s', ucfirst($attribute));
        if ( method_exists($this, $getter) && method_exists($this, $setter) ) {
            return true;
        } else if ( isset($this->attributes[$name]) ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Check whether there are any errors in the form.
     * 
     * @param string $attribute The name of attribute to check
     * @return boolean
     */
    public function hasError( $attribute=null ){
        if ( !is_null($attribute) ) {
            return !empty($this->attributes[$attribute]['errors']);
        }
        
        foreach ( $this->attributes as $attribute ) {
            if ( !empty($attribute['errors']) ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get all the errors of this form.
     * 
     * @return array
     */
    public function getErrors(){
        $errors = array();
        foreach ( $this->attributes as $name => $attribute ) {
            if ( !empty($attribute['errors']) ) {
                $errors[$name] = $attribute['errors'];
            }
        }
        return $errors;
    }
    
    /**
     * Add error to the form.
     * 
     * @param string $attribute The name of the attribute
     * @param string $message The error message
     */
    public function addError( $attribute, $message ) {
        $this->attributes[$attribute]['errors'][] = $message;
    }
    
    /* Attribute types */
    const T_INT     = 0;
    const T_STRING  = 1;
    const T_ARRAY   = 2;
    const T_NUMERIC = 3;
}
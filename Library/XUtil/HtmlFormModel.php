<?php
/**
 * Namespace
 */
namespace X\Library\XUtil;

/**
 * The html form model.
 * 
 * @author  Mihcael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class HtmlFormModel extends \stdClass {
    /**
     * This value contains all the attributes of the form model.
     * 
     * @var array
     */
    protected $attributes = array(
    /* 'attr'=>array(
     *      'value'         => 'value',
     *      'validators'    => array(),
     *      'default'       => null,
     *      'errors'        => array()
     * )
     * 
     * */
    );
    
    /**
     * Initate this model
     * 
     * @return void
     */
    public function __construct() {
        $this->describe();
        $this->init();
    }
    
    /**
     * Init the form
     * 
     * @return void
     */
    protected function init() {}
    
    /**
     * Describe the model attributes.
     * 
     * @return void
     */
    abstract protected function describe();
    
    /**
     * Add attribute to the form model.
     * The description could contains the followings:
     * -- default       : The default value of the attribute, default to null.
     * -- validators    : An array contains the validators.
     * 
     * @param string $name The name of the attribute
     * @param array $description The description of the attribute.
     * 
     * @return void
     */
    protected function addAttribute( $name, $description=array()) {
        $defaultDescription = array();
        $defaultDescription['value'] = null;
        $defaultDescription['validators'] = array();
        $defaultDescription['default'] = null;
        $defaultDescription['errors'] = array();
        
        $description = array_merge($defaultDescription, $description);
        $this->attributes[$name] = $description;
    }
    
    /**
     * Get attribute value form model
     * 
     * @param string $name The name of attributes to get.
     * 
     * @return mixed
     */
    public function __get( $name ) {
        if ( method_exists($this, sprintf('get%s', ucfirst($name))) ) {
            return call_user_func(array($this, sprintf('get%s', ucfirst($name))));
        } else {
            return $this->getValueFromAttribute($name);
        }
    }
    
    /**
     * Get the value of the attribute
     * @param unknown $name
     */
    protected function getValueFromAttribute( $name ) {
        if ( isset($this->attributes[$name]) ) {
            return $this->attributes[$name]['value'];
        } else {
            return null;
        }
    }
    
    /**
     * Set value to attribute
     * 
     * @param string $name The name of attribute
     * @param mixed $value The value to the attribute
     */
    protected function setValueToAttribute( $name, $value ) {
        $this->attributes[$name]['value'] = $value;
    }
    
    /**
     * 
     * @param unknown $attributes
     */
    public function setAttributes( $attributes ) {
        foreach ( $attributes as $name => $value ) {
            $this->$name = $value;
        }
    }
    
    /**
     * The magic method for isset
     *
     * @param string $name The name of attributes to get.
     *
     * @return boolean
     */
    public function __isset( $name ) {
        return $this->has($name);
    }
    
    /**
     * Check whether the attribute exists in the model.
     * 
     * @param string $name The name of the attribute.
     * @return boolean
     */
    public function has( $name ) {
        if ( method_exists($this, sprintf('get%s', ucfirst($name))) ) {
            return true;
        } else if ( isset($this->attributes[$name]) ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Set the value to an attribute in the model.
     * 
     * @param string $name The name of the attribute
     * @param mixed $value The value of the attribute
     * @return void
     */
    public function __set( $name, $value ) {
        if ( method_exists($this, sprintf('set%s', ucfirst($name))) ) {
            call_user_func_array(array($this, sprintf('set%s', ucfirst($name))), array($value));
        } else if ( isset($this->attributes[$name]) ) {
            $this->attributes[$name]['value'] = $value;
        } else {
            return ;
        }
    }
    
    /**
     * Validate the form
     * 
     * @return boolean
     */
    public function validate(){
        foreach ( $this->attributes as $attribute ) {
            foreach ( $attribute['validators'] as $validator ) {
                call_user_func_array($validator, array($this));
            }
        }
        return $this->hasError();
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
    
    /**
     * 
     * @param string $source
     */
    public function setup($source=null){
        if ( is_null($source) ) {
            $source = $_POST;
        }
        
        foreach ( $this->attributes as $name => $attribute ) {
            if ( isset($source[$name]) ) {
                $this->$name = $source[$name];
            } else {
                $this->$name = $attribute['default'];
            }
        }
    }
}
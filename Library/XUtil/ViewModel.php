<?php
/**
 * Namespace
 */
namespace X\Library\XUtil;

/**
 * The view model use to store the view data.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class ViewModel extends \stdClass {
    /**
     * The errors on the view model.
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Add error to current view model.
     * 
     * @param string $attribute The name of the attribute.
     * @param string $error The error message.
     */
    public function addError( $attribute, $error ) {
        $this->errors[$attribute][] = $error;
    }
    
    /**
     * Clear the errors on the form.
     * 
     * @return void
     */
    public function clearError() {
        $this->errors = array();
    }
    
    /**
     * Get errors from the view model. If the attribute 
     * is not setted, then all the errors would be returned.
     * 
     * @param string $attribute The name of the attribute.
     * @return multitype:
     */
    public function getErrors( $attribute=null ) {
        return is_null($attribute) 
            ? $this->errors 
            : $this->errors[$attribute];
    }
    
    /**
     * Wether there is any errors in the view model.
     * 
     * @return boolean
     */
    public function hasError() {
        return 0 < count($this->errors);
    }
    
    /**
     * The data of the view model
     * 
     * @var array
     */
    protected $data = array();
    
    /**
     * Set data to view model.
     * 
     * @param string $name The name of the value
     * @param mixed $value The value of the data
     */
    protected function setData( $name, $value ) {
        $this->data[$name] = $value;
    }
    
    /**
     * Get data from view model.
     * 
     * @param string $name The name of value.
     * 
     * @return mixed
     */
    protected function getData( $name ) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    /**
     * Get value from view data.
     * 
     * @param string $name The name of value.
     * @return mixed
     */
    public function __get( $name ) {
        return $this->getData($name);
    }
    
    /**
     * Set data to view model.
     *
     * @param string $name The name of the value
     * @param mixed $value The value of the data
     */
    public function __set( $name, $value ) {
        $this->setData($name, $value);
    }
}
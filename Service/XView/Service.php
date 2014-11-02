<?php
/**
 * Namespace defination
 */
namespace X\Service\XView;

/**
 * The view service
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        register_shutdown_function(array($this, 'autoDisplayHandler'));
    }
    
    /**
     * This value contains all loaded view instance.
     * -- key : {string} the name of view
     * -- value: {X\Service\XView\View} the instance of view handler.
     *
     * @var X\Service\XView\View[]
     */
    protected $views = array(
    /* 'view1' => $viewObje, */
    );
    
    /**
     * The type of view for html page.
     *
     * @var string
     */
    const VIEW_TYPE_HTML = 'Html';
    
    /**
     * Create a new view handler and load it into view service.
     *
     * @param string $name The name of the view.
     * @param string $type The type of the view, the value of it could be a name of view
     *                     handler class or the const values of XViewService::VIEW_TYPE_*
     * @return X\Service\XView\View
     */
    public function create( $name, $type ) {
        if ( isset($this->views[$name]) ) {
            throw new Exception(sprintf('View handler "%s" already exists.', $name));
        }
        
        $viewHandler = $type;
        if ( !class_exists($viewHandler, false) ) {
            $viewHandler = sprintf('\\X\\Service\\XView\\Core\\Handler\\%s', $type);
        }
        
        $view = new $viewHandler();
        if ( !($view instanceof \X\Service\XView\Core\View) ) {
            throw new Exception(sprintf('"%s" is not a valid view handler.', $viewHandler));
        }
        
        $this->views[$name] = $view;
        return $view;
    }
    
    /**
     * Check the view by given name. return  true if the view exists or false if not.
     * 
     * @param string $name The name of view
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->views[$name]);
    }
    
    /**
     * Get view instance by given name.
     * 
     * @param string $name The name of view.
     * @return X\Service\XView\View
     */
    public function get($name){
        if ( !$this->has($name) ) {
            throw new Exception(sprintf('Can not find view "%s".', $name));
        }
        
        return $this->views[$name];
    }
    
    /**
     * Get the name list of views.
     *
     * @return \X\View\XView
     */
    public function getList(){
        return array_keys($this->views);
    }
    
    /**
     * The name of active view.
     *
     * @var string
     */
    protected $activedView = null;
    
    /**
     * Set given view to active.
     *
     * @param string $name The name of view to active.
     * @return void
     */
    public function activeView($name){
        $this->activedView = $name;
    }
    
    /**
     * Whether it would auto dispaly the view.
     * 
     * @var boolean
     */
    protected $isAutoDisplay = true;
    
    /**
     * Enable the auto display function.
     * 
     * @return void
     */
    public function enableAutoDisplay() {
        $this->isAutoDisplay = true;
    }
    
    /**
     * Disable the auto display function
     * 
     * @return void
     */
    public function disableAutoDisplay() {
        $this->isAutoDisplay = false;
    }
    
    /**
     * The auto display handler to display active view automaticly.
     * 
     * @return void
     */
    public function autoDisplayHandler() {
        if ( !$this->isAutoDisplay || is_null($this->activedView) ) {
            return;
        }
        
        $this->display();
    }
    
    /**
     * Display the active view. If there is no active view, then
     * nothing would be displayed.
     * 
     * @return void
     */
    public function display(){
        if ( is_null($this->activedView) ) {
            return;
        }
        $this->views[$this->activedView]->display();
    }
}

return __NAMESPACE__;
<?php
namespace X\Service\XView\Core\Util\HtmlView;
use X\Core\Util\ConfigurationArray;
/**
 * 
 */
class ParticleView {
    /**
     * @var mixed
     */
    private $handler = null;
    
    /**
     * @var ConfigurationArray
     */
    private $data = null;
    
    /**
     * @var ConfigurationArray
     */
    private $option = null;
    
    /**
     * @var string
     */
    private $content = null;
    
    /**
     * @param string $path
     */
    public function __construct( $handler ) {
        $this->handler = $handler;
        $this->data = new ConfigurationArray();
        $this->option = new ConfigurationArray();
    }
    
    /**
     * @return \X\Core\Util\ConfigurationArray
     */
    public function getDataManager() {
        return $this->data;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationArray
     */
    public function getOptionManager() {
        return $this->option;
    }
    
    /**
     * Displayt the content of particle.
     */
    public function display() {
        echo $this->toString();
    }
    
    /**
     * @return string
     */
    public function toString() {
        if ( null === $this->content ) {
            $this->content = $this->doRender();
        }
        return $this->content;
    }
    
    /**
     * Do render by given information.
     * @param mixed $view The view to render.
     * @param mixed $data The data that view would used.
     * @return string
     */
    private function doRender() {
        if ( is_file($this->handler) ) {
            extract($this->getDataManager()->toArray(), EXTR_OVERWRITE);
            ob_start();
            ob_implicit_flush(false);
            require $this->handler;
            return ob_get_clean();
        } else if ( is_callable($this->handler) ) {
            return call_user_func_array($this->handler, $this->getDataManager()->toArray(), $this->getOptionManager()->toArray());
        } else if ( is_string($this->handler) ) {
            return $this->handler;
        } else {
            return null;
        }
    }
    
    /**
     * 
     */
    public function cleanUp() {
        $this->content = null;
    }
}
<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
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
     * @var ParticleViewManager
     */
    private $manager = null;
    
    /**
     * @param unknown $handler
     * @param ParticleViewManager $manager
     */
    public function __construct( $handler, ParticleViewManager $manager ) {
        $this->handler = $handler;
        $this->data = new ConfigurationArray();
        $this->option = new ConfigurationArray();
        $this->manager = $manager;
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
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleViewManager
     */
    public function getManager() {
        return $this->manager;
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
        if ( is_string($this->handler) && is_file($this->handler) ) {
            extract($this->getViewRenderData());
            ob_start();
            ob_implicit_flush(false);
            require $this->handler;
            return ob_get_clean();
        } else if ( is_callable($this->handler) ) {
            return call_user_func_array($this->handler, array($this->getViewRenderData(), $this->getOptionManager()->toArray()));
        } else if ( is_string($this->handler) ) {
            return $this->handler;
        } else {
            return null;
        }
    }
    
    /**
     * @return multitype:
     */
    private function getViewRenderData(){
        return array_merge(
                $this->manager->getHost()->getDataManager()->toArray(),
                $this->getDataManager()->toArray()
        );
    }
    
    /**
     * 
     */
    public function cleanUp() {
        $this->content = null;
    }
}
<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class ParticleViewManager {
    /**
     * @var array
     */
    private $particles = null;
    
    /**
     * Load paritcle view into current view.
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleView
     */
    public function load( $name, $handler ) {
        $this->particles[$name] = new ParticleView($handler);
        return $this->particles[$name];
    }
    
    /**
     * Chech that whether the paricle exists.
     * @param string $name The name of particle view.
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->particles[$name]);
    }
    
    /**
     * Get the paticle information by given name.
     * @param string $name The particle name.
     * @throws Exception
     * @return array
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Can not find particle view "'.$name.'"');
        }
        return $this->particles[$name];
    }
    
    /**
     * Get all particles of current view.
     * @return array
     */
    public function getList() {
        return array_keys($this->particles);
    }
    
    /**
     * Remove particle view from current view.
     * @param string $name The name of particle to remove.
     */
    public function remove( $name ) {
        if ( isset($this->particles[$name]) ) {
            unset($this->particles[$name]);
        }
    }
    
    /**
     * 
     */
    public function toString() {
        $contents = array();
        foreach ( $this->particles as $particle ) {
            $contents[] = $particle->toString();
        }
        return implode("\n", $contents);
    }
    
    /**
     * 
     */
    public function cleanUp() {
        foreach ( $this->particles as $particle ) {
            $particle->cleanUp();
        }
    }
}
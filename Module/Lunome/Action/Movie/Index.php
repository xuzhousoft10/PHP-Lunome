<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\VisualMain;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMain { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( ) {
        $name   = 'MOVIE_INDEX';
        $path   = $this->getParticleViewPath('Movie/Index');
        $option = array(); 
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
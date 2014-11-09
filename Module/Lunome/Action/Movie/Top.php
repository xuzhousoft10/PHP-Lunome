<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 * @method \X\Module\Lunome\Service\Movie getMovieService()
 */
class Top extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Unmark::runAction()
     */
    public function runAction() {
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        
        $interestedList = $this->getMovieService()->getTopList();
        
        /* Load top view */
        $name   = 'MEDIA_TOP';
        $path   = $this->getParticleViewPath('Movie/Top');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
<?php
/**
 * The action file for movie/poster/view action.
 */
namespace X\Module\Backend\Util\Action\Media\Poster;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Media\Visual;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Index extends Visual { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        $hasPoster = $this->getMediaService()->hasPoster($id);
        
        $name = 'POSTER_INDEX';
        $path = $this->getParticleViewPath('Util/Media/Poster/Index');
        $option = array();
        $data = array('mediaId'=>$id, 'returnURL'=>$this->getReferer(), 'hasPoster'=>$hasPoster, 'type'=>$this->getMediaType());
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Backend\Util\Action\Media\Visual::getMediaType()
     */
    protected function getMediaType() {
        $class = get_class($this);
        $class = explode('\\', $class);
        array_pop($class);
        array_pop($class);
        $media = array_pop($class);
        return strtolower($media);
    }
}
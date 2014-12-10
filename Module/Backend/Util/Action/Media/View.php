<?php
/**
 *
 */
namespace X\Module\Backend\Util\Action\Media;

/**
 *
 */
use X\Core\X;

/**
 *
 */
abstract class View extends Visual {
    /**
     *
     * @param number $page
     */
    public function runAction( $id ) {
        $media = $this->getMediaService()->get($id);
        $mediaType = $this->getMediaType();
        
        $name   = 'MEDIA_VIEW';
        $path   = $this->getParticleViewPath(ucfirst($mediaType).'/View');
        $option = array();
        $data   = array('media'=>$media, 'type'=>$this->getMediaType());
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
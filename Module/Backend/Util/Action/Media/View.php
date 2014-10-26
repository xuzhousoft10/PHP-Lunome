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
        
        $name   = 'MEDIA_VIEW';
        $path   = $this->getParticleViewPath('Util/Media/View');
        $option = array();
        $data   = array('media'=>$media, 'type'=>$this->getMediaType());
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
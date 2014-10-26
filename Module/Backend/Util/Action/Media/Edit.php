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
abstract class Edit extends Visual {
    /**
     *
     * @param number $page
     */
    public function runAction( $id=null, $save=false ) {
        $mediaType = $this->getMediaType();
        
        $media = array();
        $mediaService = $this->getMediaService();
        if ( null !== $id ) {
            $media = $mediaService->get($id);
        }
        
        if ( false !== $save ) {
            $media = $_POST['media'];
            $media = (null === $id) ? $mediaService->add($media) : $mediaService->update($id, $media);
            $this->gotoURL('/index.php?module=backend&action='.$mediaType.'/index');
        }
        
        $name   = 'MEDIA_EDIT';
        $path   = $this->getParticleViewPath(ucfirst($mediaType).'/Edit');
        $option = array();
        $data   = array('media'=>$media, 'type'=>$mediaType);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
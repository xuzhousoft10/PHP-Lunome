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
abstract class Index extends Visual {
    /**
     * 
     * @param number $page
     */
    public function runAction( $page=1 ) { 
        $mediaType = $this->getMediaType();
        $mediaService = $this->getMediaService();
        $medias = $mediaService->findAll(null, ($page-1)*20, 10);
        
        /* Load index view. */
        $name   = 'MEDIA_INDEX';
        $path   = $this->getParticleViewPath('Util/Media/Index');
        $option = array();
        $data   = array('medias'=>$medias, 'type'=>$mediaType);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load pager view */
        $pagerUrl = sprintf('/index.php?module=backend&action=%s/index&page=%%d', $mediaType);
        $count = $mediaService->count();
        $name   = 'UTIL_PAGER';
        $path   = $this->getParticleViewPath('Util/Pager');
        $option = array();
        $data   = array('total'=>$count, 'current'=>$page, 'size'=>20, 'url'=>$pagerUrl);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
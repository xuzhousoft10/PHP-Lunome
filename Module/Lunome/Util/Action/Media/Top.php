<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;

/**
 * Visual action class
 */
abstract class Top extends Visual {
    /**
     * 
     */
    protected function runAction() {
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        $service = $this->getMediaService();
        $mediaType = $this->getMediaType();
        
        $serviceClass = new \ReflectionClass($service);
        $consts = $serviceClass->getConstants();
        foreach ( $consts as $name => $value ) {
            if ( 'MARK_' !== substr($name, 0, 5) || 0 === $value ) {
                unset($consts[$name]);
            }
        }
        $marks = $service->getMarkNames();
        $list = array();
        foreach ( $consts as $name => $mark ) {
            $list[$mark] = array();
            $list[$mark]['label'] = $marks[$mark];
            $list[$mark]['list'] = $service->getTopList($mark, 100); 
        }
        $mediaName = $service->getMediaName();
        
        /* Load top view */
        $name   = 'MEDIA_TOP';
        $path   = $this->getParticleViewPath('Util/Media/Top');
        $option = array();
        $data   = array(
            'list'      => $list, 
            'length'    => 100, 
            'type'      => $mediaType,
            'name'      => $service->getMediaName(),
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = sprintf('%s排行榜 -- TOP %s',$mediaName, 100);
    }
    
    /**
     * @return string
     */
    protected function getMediaType() {
        $class = explode('\\', get_class($this));
        array_pop($class);
        $media = array_pop($class);
        return $media;
    }
    
    /**
     * @return \X\Module\Lunome\Util\Service\Media
     */
    protected function getMediaService() {
        return $this->getService($this->getMediaType());
    }
}
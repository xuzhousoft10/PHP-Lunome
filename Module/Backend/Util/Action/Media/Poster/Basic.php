<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action\Media\Poster;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Media\Basic as MediaBasic;

/**
 * 
 */
class Basic extends MediaBasic {
    /**
     * @return \X\Module\Lunome\Util\Service\Media
     */
    protected function getMediaService() {
        $class = explode('\\', get_class($this));
        array_pop($class);
        array_pop($class);
        $media = array_pop($class);
        return $this->getService($media);
    }
}
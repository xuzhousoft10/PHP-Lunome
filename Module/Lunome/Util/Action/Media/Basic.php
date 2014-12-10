<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic as ActionBasic;

/**
 * Visual action class
 */
abstract class Basic extends ActionBasic {
    /**
     * @return \X\Module\Lunome\Util\Service\Media
     */
    protected function getMediaService() {
        $class = explode('\\', get_class($this));
        array_pop($class);
        $media = array_pop($class);
        return $this->getService($media);
    }
}
<?php
/**
 * 
 */
namespace X\Module\Backend\Util\Action\Media;

/**
 * 
 */
use X\Module\Backend\Util\Action\Visual as UtilVisual;

/**
 * 
 */
class Visual extends UtilVisual {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Backend\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        $this->setActiveItem($this->getActiveItem());
    }
    
    /**
     *
     * @return string
     */
    protected function getMediaType() {
        $class = get_class($this);
        $class = explode('\\', $class);
        array_pop($class);
        $media = array_pop($class);
        return strtolower($media);
    }
    
    /**
     *
     * @return \X\Module\Lunome\Util\Service\Media
     */
    protected function getMediaService() {
        $mediaType = $this->getMediaType();
        $service = $this->getService(ucfirst($mediaType));
        return $service;
    }
    
    /**
     *
     */
    protected function getActiveItem() {
        $media = $this->getMediaType();
        $media .= '_management';
        return $media;
    }
}
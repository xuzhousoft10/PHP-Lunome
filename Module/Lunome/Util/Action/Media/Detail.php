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
abstract class Detail extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        parent::afterRunAction();
    }
    
    /**
     * @var unknown
     */
    protected $id = null;
    
    /**
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $id ) {
        $this->id = $id;
        $mediaType = $this->getMediaType();
        $service = $this->getService($mediaType);
        
        /* Load detail view */
        $media = $service->get($id);
        if ( 0 === $media['has_cover']*1 ) {
            $media['cover'] = $service->getMediaDefaultCoverURL();
        } else {
            $media['cover'] = $service->getMediaCoverURL($media['id']);
        }
        $markCount = array();
        foreach ( $this->getMediaMarks() as $markValue ) {
            $markCount[$markValue]  = $service->countMarked($markValue, $id, null);
        }
        $myMark = $service->getMark($id);
        $names  = $this->getMarkNames();
        $styles = $this->getMarkStyles();
        
        $name   = 'MEDIA_DETAIL';
        $path   = $this->getParticleViewPath('Util/Media/Detail');
        $option = array();
        $data   = array(
            'media'     => $media, 
            'markCount' => $markCount, 
            'myMark'    => $myMark,
            'markStyles'=> $styles,
            'markNames' => $names,
            'mediaType' => $mediaType,
            'mediaName' => $this->getMediaName(),
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = $media['name'];
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
     * @return array
     */
    protected function getMediaMarks() {
        $class = new \ReflectionClass($this->getService($this->getMediaType()));
        $consts = $class->getConstants();
        $marks = array();
        foreach ( $consts as $name => $value ) {
            if ( 'MARK_' === substr($name, 0, 5) ) {
                $marks[] = $value;
            }
        }
        return $marks;
    }
    
    /**
     * 
     * @param unknown $mark
     */
    abstract protected function getMarkNames();
    abstract protected function getMarkStyles();
    abstract protected function getMediaName();
}
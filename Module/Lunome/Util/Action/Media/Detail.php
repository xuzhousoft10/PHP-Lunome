<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Util\Exception as LunomeException;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * Visual action class
 */
abstract class Detail extends Visual {
    /**
     * @var boolean
     */
    protected $isGuest = false;
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        $this->isGuest = $this->getService(UserService::getServiceName())->getIsGuest();
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
        /* @var $service \X\Module\Lunome\Util\Service\Media */
        $service = $this->getService($mediaType);
        
        /* Load detail view */
        try {
            $media = $service->get($id);
        } catch ( LunomeException $e ) {
            $this->throw404();
        }
        if ( 0 === $media['has_cover']*1 ) {
            $media['cover'] = $service->getMediaDefaultCoverURL();
        } else {
            $media['cover'] = $service->getCoverURL($media['id']);
        }
        $media = $this->afterFindTheModel($media);
        $markCount = array();
        foreach ( $this->getMediaMarks() as $markValue ) {
            $markCount[$markValue]  = $service->countMarked($markValue, $id, null);
        }
        $myMark = $this->isGuest ? 0 : $service->getMark($id);
        $names  = $service->getMarkNames();
        $styles = $this->getMarkStyles();
        
        $userData = $this->isGuest ? null : $this->getUserService()->getAccount()->get($this->getUserService()->getCurrentUserId());
        $name   = 'MEDIA_DETAIL';
        $path   = $this->getParticleViewPath('Util/Media/Detail');
        $option = array();
        $data   = array(
            'media'         => $media, 
            'markCount'     => $markCount, 
            'myMark'        => $myMark,
            'markStyles'    => $styles,
            'markNames'     => $names,
            'mediaType'     => $mediaType,
            'mediaName'     => $service->getMediaName(),
            'shareMessage'  => $this->getShareMessage($media['id'], $myMark),
            'isGuestUser'   => $this->isGuest,
            'currentUser'   => $userData,
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
    abstract protected function getMarkStyles();
    
    /**
     *
     * @param unknown $model
     */
    protected function afterFindTheModel( $media ) {
        return $media;
    }
    
    /**
     * 
     * @param unknown $myMark
     * @return string
     */
    protected function getShareMessage( $mediaId, $myMark ) {
        return '';
    }
}
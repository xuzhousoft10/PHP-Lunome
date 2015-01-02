<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service;
use X\Module\Lunome\Util\Exception as LunomeException;
use X\Module\Lunome\Service\User\Service as UserService;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Detail extends Visual {
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
    
        $userData = $this->isGuest ? null : $this->getUserService()->getAccount()->getInformation($this->getUserService()->getCurrentUserId());
        $name   = 'MEDIA_DETAIL';
        $path   = $this->getParticleViewPath('Movie/Detail');
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
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::afterFindTheModel()
     */
    protected function afterFindTheModel($media) {
        $media['region'] = $this->getMovieService()->getRegionById($media['region_id']);
        $media['language'] = $this->getMovieService()->getLanguageById($media['language_id']);
        $media['category'] = $this->getMovieService()->getCategoriesByMovieId($media['id']);
        $media['directors'] = $this->getMovieService()->getDirectors($media['id']);
        $media['actors'] = $this->getMovieService()->getActors($media['id']);
        
        return $media;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getShareMessage()
     */
    protected function getShareMessage($mediaId, $myMark) {
        $message = '';
        $categories = $this->getMovieService()->getCategoriesByMovieId($mediaId);
        if ( 0 < count($categories) ) {
            $index = rand(0, count($categories)-1);
            /* @var $category \X\Module\Lunome\Model\Movie\MovieCategoryModel */
            $category = $categories[$index];
            switch ( $myMark ) {
            case Service::MARK_INTERESTED :
                $message = $category->beg_message;
                break;
            case Service::MARK_WATCHED :
                $message = $category->recommend_message;
                break;
            default:
                $message = $category->share_message;
                break;
            }
        }
        
        if ( empty($message) ) {
            switch ( $myMark ) {
            case Service::MARK_INTERESTED :
                $message = "怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《{name}》呗？";
                break;
            case Service::MARK_WATCHED :
                $message = "看完《{name}》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ";
                break;
            default:
                $message = "";
                break;
            }
        }
        return $message;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Detail::getMarkStyles()
     */
    protected function getMarkStyles() {
        return array(
            Service::MARK_UNMARKED      => 'warning',
            Service::MARK_INTERESTED    => 'success',
            Service::MARK_WATCHED       => 'info',
            Service::MARK_IGNORED       => 'default'
        );
    }
}
<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Tv;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Tv\Service;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Tv\Service getTvService()
 * @author Unknown
 */
class Detail extends Visual { 
    /**
     * 
     */
    public function runAction( $id ) {
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        
        /* Load detail view */
        $media = $this->getTvService()->get($id);
        $markCount = array();
        $markCount[Service::MARK_WATCHED]   = $this->getTvService()->countMarked(Service::MARK_WATCHED, $id, null);
        $markCount[Service::MARK_INTERESTED]= $this->getTvService()->countMarked(Service::MARK_INTERESTED, $id, null);
        $markCount[Service::MARK_IGNORED]   = $this->getTvService()->countMarked(Service::MARK_IGNORED, $id, null); 
        $myMark = $this->getTvService()->getMark($id);
        $name   = 'MEDIA_DETAIL';
        $path   = $this->getParticleViewPath('Tv/Detail');
        $option = array();
        $data   = array('media'=>$media, 'markCount'=>$markCount, 'myMark'=>$myMark);
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load posters view */
        $media = $this->getTvService()->get($id);
        $name   = 'MEDIA_DETAIL_POSTER';
        $path   = $this->getParticleViewPath('Util/Media/Poster');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load preview view */
        $media = $this->getTvService()->get($id);
        $name   = 'MEDIA_DETAIL_PRIVIEW';
        $path   = $this->getParticleViewPath('Util/Media/Preview');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        /* Load comment view */
        $media = $this->getTvService()->get($id);
        $name   = 'MEDIA_DETAIL_COMMENT';
        $path   = $this->getParticleViewPath('Util/Media/Comment');
        $option = array();
        $data   = array();
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $this->getView()->title = $media['name'];
    }
}
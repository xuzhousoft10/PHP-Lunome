<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 * @method \X\Module\Lunome\Service\Movie getMovieService()
 */
class Top extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Unmark::runAction()
     */
    public function runAction() {
        $this->getView()->loadLayout($this->getLayoutViewPath('BlankThin'));
        $list = array(
            Service::MARK_INTERESTED => array(
                'label' => '想看',
                'list'  => $this->getMovieService()->getTopList(Service::MARK_INTERESTED, 100)
            ),
            Service::MARK_WATCHED => array(
                'label' => '以看',
                'list'  => $this->getMovieService()->getTopList(Service::MARK_WATCHED, 100)
            ),
            Service::MARK_IGNORED => array(
                'label' => '不喜欢',
                'list'  => $this->getMovieService()->getTopList(Service::MARK_IGNORED, 100)
            ),
        );
        
        /* Load top view */
        $name   = 'MEDIA_TOP';
        $path   = $this->getParticleViewPath('Movie/Top');
        $option = array();
        $data   = array('list'=>$list, 'length'=>100);
        $this->getView()->loadParticle($name, $path, $option, $data);
    }
}
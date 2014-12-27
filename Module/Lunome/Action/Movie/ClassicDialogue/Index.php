<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\ClassicDialogue;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Index extends Visual { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $page=1 ) {
        if ( 0 >= $page*1 ) {
            $page = 1;
        }
        
        $pageSize = 10;
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $dialogues = $movieService->getClassicDialogues($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $dialogues as $index => $dialogue ) {
            $dialogues[$index] = $dialogue->toArray();
        }
        
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $movieService->countClasicDialogues($id)) ? false : $page+1;
        
        $isWatched = MovieService::MARK_WATCHED === $movieService->getMark($id);
        $name   = 'CLASSIC_DIALOGUES_INDEX';
        $path   = $this->getParticleViewPath('Movie/ClassicDialogues');
        $option = array();
        $data   = array('dialogues'=>$dialogues, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}
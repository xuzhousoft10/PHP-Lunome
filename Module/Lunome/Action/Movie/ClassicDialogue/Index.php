<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\ClassicDialogue;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/classicDialogue/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_classic_dialogue_page_size');
        
        $page = intval($page);
        if ( 0 >= $page ) {
            $page = 1;
        }
        
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